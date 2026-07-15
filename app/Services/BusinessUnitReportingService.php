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

        // 2. Total revenue (sum of actual payments recorded in the given date range)
        $paymentQuery = \App\Models\Payment::query();
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $paymentQuery->whereBetween('payment_date', [$filters['start_date'], $filters['end_date']]);
        }
        if (!empty($filters['client_id'])) {
            $paymentQuery->whereHas('invoice', function ($q) use ($filters) {
                $q->where('client_id', $filters['client_id']);
            });
        }
        if (!empty($filters['business_unit_id'])) {
            $paymentQuery->whereHas('invoice', function ($q) use ($filters) {
                $q->where('business_unit_id', $filters['business_unit_id']);
            });
        }
        $totalRevenue = $paymentQuery->sum('amount');
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
        $statusBreakdown = (clone $invoiceQuery)
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
            ->groupBy('status')
            ->get();

        return [
            'total_billed' => (float) $totalBilled,
            'total_invoices_count' => $totalCount,
            'total_revenue' => (float) $totalRevenue,
            'paid_invoices_count' => $paidCount,
            'collection_rate' => (float) $collectionRate,
            'pending_outstanding' => (float) $pendingOutstanding,
            'overdue_outstanding' => (float) $overdueOutstanding,
            'total_outstanding' => (float) ($pendingOutstanding + $overdueOutstanding),
            'status_breakdown' => $statusBreakdown,
        ];
    }

    /**
     * Get cash flow trend (revenue vs receivables) for a given date range or last X months.
     */
    public function getMonthlyTrend(array $filters = [], int $months = 6): array
    {
        $trendData = [];
        $locale = app()->getLocale();

        $monthNamesId = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        $monthNamesEn = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        // Extract base filters (without date limits) to allow monthly querying
        $baseFilters = $filters;
        unset($baseFilters['start_date'], $baseFilters['end_date']);

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfMonth();
            $end = Carbon::parse($endDate)->startOfMonth();
            $monthsList = [];
            
            // Limit to maximum of 24 months to avoid performance issues
            $safetyCounter = 0;
            while ($start->lessThanOrEqualTo($end) && $safetyCounter < 24) {
                $monthsList[] = $start->copy();
                $start->addMonth();
                $safetyCounter++;
            }
        } else {
            $today = Carbon::today();
            $monthsList = [];
            for ($i = $months - 1; $i >= 0; $i--) {
                $monthsList[] = $today->copy()->subMonths($i)->startOfMonth();
            }
        }

        foreach ($monthsList as $monthDate) {
            $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
            $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();

            $monthlyFilters = array_merge($baseFilters, [
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
            ]);

            $stats = $this->getSummaryStats($monthlyFilters);
            $monthNum = $monthDate->month;
            $label = $locale === 'id' ? $monthNamesId[$monthNum] : $monthNamesEn[$monthNum];

            $trendData[] = [
                'month_label' => $label . ' ' . $monthDate->year,
                'total_billed' => $stats['total_billed'],
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

        // Retrieve all business units to ensure past transaction history is preserved on reports
        return BusinessUnit::withCount(['invoices as total_orders' => function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            }])
            ->select('business_units.*')
            ->selectSub(function ($query) use ($startDate, $endDate) {
                $query->selectRaw('COALESCE(SUM(payments.amount), 0)')
                    ->from('payments')
                    ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
                    ->whereColumn('invoices.business_unit_id', 'business_units.id');
                if ($startDate && $endDate) {
                    $query->whereBetween('payments.payment_date', [$startDate, $endDate]);
                }
            }, 'total_revenue')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function ($unit) {
                /**
                 * Business Unit Profit-Sharing Calculation
                 * ----------------------------------------------------
                 * 1. Gross Revenue: Total revenue from paid invoices.
                 * 2. Fee Percentage: Unique fee rate stored on the unit.
                 * 3. Fee Nominal: Fee share (Gross Revenue * Fee Percentage / 100).
                 * 4. Net Revenue: Remaining revenue after fee deduction.
                 */
                $unit->gross_revenue = (float) ($unit->total_revenue ?? 0.00);
                
                // Fallback to 0% for backward compatibility
                $unit->fee_percentage = (float) ($unit->fee_percentage ?? 0.00);
                
                // Calculate fee nominal and round to 2 decimal places for financial precision
                $unit->fee_nominal = round(($unit->gross_revenue * $unit->fee_percentage) / 100, 2);
                
                // Net Revenue calculation
                $unit->net_revenue = round($unit->gross_revenue - $unit->fee_nominal, 2);
                
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
