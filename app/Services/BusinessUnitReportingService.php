<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\BusinessUnit;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BusinessUnitReportingService
{
    /**
     * Get summary metrics for a given set of filters.
     * Supported filters: business_unit_id, client_id, start_date, end_date
     */
    public function getSummaryStats(array $filters = []): array
    {
        $invoiceQuery = Invoice::query();
        $this->applyFilters($invoiceQuery, $filters);

        // 1. Total billed amount and count
        $totalBilled = (clone $invoiceQuery)->sum('total');
        $totalCount = (clone $invoiceQuery)->count();

        // 2. Total revenue (sum of invoice totals where status is 'paid')
        $totalRevenue = (clone $invoiceQuery)->where('status', 'paid')->sum('total');
        $paidCount = (clone $invoiceQuery)->where('status', 'paid')->count();

        // 3. Collection Rate
        $collectionRate = $totalCount > 0 ? ($paidCount / $totalCount) * 100 : 0;

        // 4. Outstanding receivables (unpaid portion, subtracting partial payments)
        $unpaidInvoices = (clone $invoiceQuery)
            ->where('status', '!=', 'paid')
            ->withSum('payments', 'amount')
            ->get();

        $today = Carbon::today()->toDateString();
        $pendingOutstanding = 0;
        $overdueOutstanding = 0;

        foreach ($unpaidInvoices as $invoice) {
            $remaining = max(0, $invoice->total - ($invoice->payments_sum_amount ?? 0));
            
            if ($invoice->due_date && $invoice->due_date->toDateString() < $today) {
                $overdueOutstanding += $remaining;
            } else {
                $pendingOutstanding += $remaining;
            }
        }

        return [
            'total_billed' => (float) $totalBilled,
            'total_invoices_count' => $totalCount,
            'total_revenue' => (float) $totalRevenue,
            'paid_invoices_count' => $paidCount,
            'collection_rate' => (float) $collectionRate,
            'pending_outstanding' => (float) $pendingOutstanding,
            'overdue_outstanding' => (float) $overdueOutstanding,
            'total_outstanding' => (float) ($pendingOutstanding + $overdueOutstanding),
        ];
    }

    /**
     * Get cash flow trend (revenue vs receivables) for a given date range or last X months.
     */
    public function getMonthlyTrend(array $filters = [], int $months = 6): array
    {
        $trendData = [];
        $today = Carbon::today();
        $locale = app()->getLocale();

        $monthNamesId = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        $monthNamesEn = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthDate = $today->copy()->subMonths($i);
            $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
            $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();

            // Set up monthly filters
            $monthlyFilters = array_merge($filters, [
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
            ]);

            $stats = $this->getSummaryStats($monthlyFilters);
            $monthNum = $monthDate->month;
            $label = $locale === 'id' ? $monthNamesId[$monthNum] : $monthNamesEn[$monthNum];

            $trendData[] = [
                'month_label' => $label . ' ' . $monthDate->year,
                'revenue' => $stats['total_revenue'],
                'receivables' => $stats['total_outstanding'],
            ];
        }

        return $trendData;
    }

    /**
     * Get top clients by revenue under the current filtered context.
     */
    public function getTopClients(array $filters = [], int $limit = 5)
    {
        $businessUnitId = $filters['business_unit_id'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        $query = Client::whereHas('invoices', function ($q) use ($businessUnitId, $startDate, $endDate) {
            $q->where('status', 'paid');
            if ($businessUnitId) {
                $q->where('business_unit_id', $businessUnitId);
            }
            if ($startDate && $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        });

        return $query->withSum(['invoices' => function ($q) use ($businessUnitId, $startDate, $endDate) {
                $q->where('status', 'paid');
                if ($businessUnitId) {
                    $q->where('business_unit_id', $businessUnitId);
                }
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            }], 'total')
            ->orderByDesc('invoices_sum_total')
            ->take($limit)
            ->get();
    }

    /**
     * Get list of business units with aggregated metrics.
     */
    public function getBusinessUnitsSummary(array $filters = [])
    {
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        return BusinessUnit::where('is_active', true)
            ->withCount(['invoices as total_orders' => function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            }])
            ->withSum(['invoices as total_revenue' => function ($q) use ($startDate, $endDate) {
                $q->where('status', 'paid');
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            }], 'total')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function ($unit) {
                $unit->total_revenue = (float) ($unit->total_revenue ?? 0);
                return $unit;
            });
    }

    /**
     * Helper to apply common filters to invoice queries.
     */
    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['business_unit_id'])) {
            $query->where('business_unit_id', $filters['business_unit_id']);
        }

        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }
    }
}
