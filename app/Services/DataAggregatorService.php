<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;

class DataAggregatorService
{
    /**
     * Calculate monthly growth trend of revenue (paid invoices).
     *
     * @param string $locale
     * @return array
     */
    public function getRevenueTrend(string $locale = 'id'): array
    {
        $now = Carbon::now();
        $startOfCurrMonth = $now->copy()->startOfMonth()->toDateTimeString();
        $endOfCurrMonth = $now->copy()->endOfMonth()->toDateTimeString();
        
        $startOfPrevMonth = $now->copy()->subMonth()->startOfMonth()->toDateTimeString();
        $endOfPrevMonth = $now->copy()->subMonth()->endOfMonth()->toDateTimeString();

        // Query only paid invoices for gross revenue comparison
        $currRevenue = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startOfCurrMonth, $endOfCurrMonth])
            ->sum('total');

        $prevRevenue = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startOfPrevMonth, $endOfPrevMonth])
            ->sum('total');

        $growthPercent = 0.0;
        if ($prevRevenue > 0) {
            $growthPercent = (($currRevenue - $prevRevenue) / $prevRevenue) * 100;
        } elseif ($currRevenue > 0) {
            $growthPercent = 100.0; // 100% growth if there was no revenue last month
        }

        $formattedCurr = "Rp " . number_format($currRevenue, 0, ',', '.');
        $formattedPrev = "Rp " . number_format($prevRevenue, 0, ',', '.');
        $absPercent = number_format(abs($growthPercent), 1, ',', '.');

        if ($growthPercent > 0) {
            $insight = $locale === 'en'
                ? "Revenue this month ({$formattedCurr}) increased by {$absPercent}% compared to last month ({$formattedPrev})."
                : "Penghasilan bulan ini ({$formattedCurr}) naik {$absPercent}% dibanding bulan lalu ({$formattedPrev}).";
        } elseif ($growthPercent < 0) {
            $insight = $locale === 'en'
                ? "Revenue this month ({$formattedCurr}) decreased by {$absPercent}% compared to last month ({$formattedPrev})."
                : "Penghasilan bulan ini ({$formattedCurr}) turun {$absPercent}% dibanding bulan lalu ({$formattedPrev}).";
        } else {
            $insight = $locale === 'en'
                ? "Revenue this month ({$formattedCurr}) is stable compared to last month ({$formattedPrev})."
                : "Penghasilan bulan ini ({$formattedCurr}) stabil dibanding bulan lalu ({$formattedPrev}).";
        }

        return [
            'current_revenue' => (float)$currRevenue,
            'previous_revenue' => (float)$prevRevenue,
            'growth_percent' => (float)$growthPercent,
            'insight' => $insight
        ];
    }

    /**
     * Get invoices due tomorrow.
     *
     * @param string $locale
     * @return array
     */
    public function getInvoicesDueTomorrow(string $locale = 'id'): array
    {
        $tomorrowStart = Carbon::tomorrow()->startOfDay()->toDateTimeString();
        $tomorrowEnd = Carbon::tomorrow()->endOfDay()->toDateTimeString();

        $invoices = Invoice::with('client')
            ->whereIn('status', ['sent', 'pending', 'dp'])
            ->whereBetween('due_date', [$tomorrowStart, $tomorrowEnd])
            ->get();

        $totalAmount = $invoices->sum('total');
        $count = $invoices->count();

        $list = [];
        foreach ($invoices as $inv) {
            $clientName = $inv->client ? $inv->client->nama_client : 'Unknown Client';
            $list[] = "* **Invoice #{$inv->invoice_number}** oleh {$clientName} - Rp " . number_format($inv->total, 0, ',', '.');
        }

        $listStr = count($list) > 0 ? implode("\n", $list) : ($locale === 'en' ? "- No invoices due tomorrow." : "- Tidak ada invoice jatuh tempo besok.");

        $text = $locale === 'en'
            ? "### 📅 Invoices Due Tomorrow Report\n\n" .
              "Currently, there are **{$count} invoices** due tomorrow with a total outstanding of **Rp " . number_format($totalAmount, 0, ',', '.') . "**:\n\n" .
              $listStr
            : "### 📅 Laporan Invoice Jatuh Tempo Besok\n\n" .
              "Saat ini terdeteksi **{$count} invoice** yang akan jatuh tempo besok dengan total nilai penagihan sebesar **Rp " . number_format($totalAmount, 0, ',', '.') . "**:\n\n" .
              $listStr;

        return [
            'count' => $count,
            'total' => $totalAmount,
            'text' => $text
        ];
    }

    /**
     * Get financial overview (Revenue, Overdue, Collection Rate, and Trend) for AI Advisory.
     *
     * @param string $locale
     * @return array
     */
    public function getFinancialOverview(string $locale = 'id'): array
    {
        $reportingService = app(\App\Services\BusinessUnitReportingService::class);
        
        // 1. Monthly Revenue (paid invoices in current month)
        $monthlyStats = $reportingService->getSummaryStats([
            'start_date' => Carbon::now()->startOfMonth()->toDateString(),
            'end_date' => Carbon::now()->endOfMonth()->toDateString(),
        ]);
        $monthlyRevenue = $monthlyStats['total_revenue'];

        // 2. Overdue Revenue
        $globalStats = $reportingService->getSummaryStats();
        $overdueRevenue = $globalStats['overdue_outstanding'];

        // 3. Collection Rate (percentage of paid invoices count over total invoices count)
        $collectionRate = $globalStats['collection_rate'];

        // 4. Trend Analysis for last 3 months
        $threeMonthsTrend = $reportingService->getMonthlyTrend([], 3);
        $trendSummary = [];
        foreach ($threeMonthsTrend as $t) {
            $trendSummary[] = $t['month_label'] . ": Rp " . number_format($t['total_billed'], 0, ',', '.');
        }
        $trendText = implode(', ', $trendSummary);

        return [
            'monthly_revenue' => $monthlyRevenue,
            'overdue_revenue' => $overdueRevenue,
            'collection_rate' => $collectionRate,
            'trend_text' => $trendText,
        ];
    }
}
