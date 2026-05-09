<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OwnerKpiController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();

        // 1. Omzet Bulan Ini & MoM Comparison
        $currentMonthRevenue = Payment::whereMonth('payment_date', $now->month)
            ->whereYear('payment_date', $now->year)
            ->sum('amount');

        $lastMonthRevenue = Payment::whereMonth('payment_date', $lastMonth->month)
            ->whereYear('payment_date', $lastMonth->year)
            ->sum('amount');

        $revenueChange = 0;
        if ($lastMonthRevenue > 0) {
            $revenueChange = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } elseif ($currentMonthRevenue > 0) {
            $revenueChange = 100;
        }

        // 2. Unpaid Amount Breakdown
        $allInvoices = Invoice::where('status', '!=', 'paid')->get();
        $totalUnpaid = $allInvoices->sum(fn($inv) => $inv->total - $inv->payments->sum('amount'));
        
        $pendingUnpaid = Invoice::whereIn('status', ['pending', 'sent', 'partial'])
            ->where('due_date', '>=', $now->toDateString())
            ->get()
            ->sum(fn($inv) => $inv->total - $inv->payments->sum('amount'));

        $overdueUnpaid = Invoice::where('due_date', '<', $now->toDateString())
            ->where('status', '!=', 'paid')
            ->get()
            ->sum(fn($inv) => $inv->total - $inv->payments->sum('amount'));

        // 3. Repeat Customer Rate
        $totalClients = Client::count();
        $repeatClients = Client::has('invoices', '>', 1)->count();
        $repeatRate = $totalClients > 0 ? ($repeatClients / $totalClients) * 100 : 0;

        // 4. Top 5 Clients
        $topClients = Client::withCount('invoices')
            ->withSum('invoices', 'total')
            ->orderByDesc('invoices_sum_total')
            ->take(5)
            ->get()
            ->map(function ($client) {
                $lastInvoice = $client->invoices()->orderByDesc('tanggal_invoice')->first();
                $client->last_transaction = $lastInvoice ? $lastInvoice->tanggal_invoice : null;
                return $client;
            });

        // 5. Revenue Trend (Last 6 Months)
        $revenueTrend = collect(range(5, 0))->map(function ($i) use ($now) {
            $month = $now->copy()->subMonths($i);
            return [
                'month' => $month->format('M Y'),
                'revenue' => Payment::whereMonth('payment_date', $month->month)
                    ->whereYear('payment_date', $month->year)
                    ->sum('amount')
            ];
        });

        // 6. Recent Large Payments
        $recentLargePayments = Payment::with('invoice.client')
            ->orderByDesc('amount')
            ->take(5)
            ->get();

        // 7. Monthly Performance Summary
        $monthlyPerformance = [
            'created' => Invoice::whereMonth('tanggal_invoice', $now->month)
                ->whereYear('tanggal_invoice', $now->year)
                ->count(),
            'paid' => Invoice::whereMonth('tanggal_invoice', $now->month)
                ->whereYear('tanggal_invoice', $now->year)
                ->where('status', 'paid')
                ->count(),
            'overdue' => Invoice::where('due_date', '<', $now->toDateString())
                ->where('status', '!=', 'paid')
                ->count(),
        ];

        return view('owner.kpi', compact(
            'currentMonthRevenue',
            'revenueChange',
            'totalUnpaid',
            'pendingUnpaid',
            'overdueUnpaid',
            'repeatRate',
            'topClients',
            'revenueTrend',
            'recentLargePayments',
            'monthlyPerformance',
            'totalClients'
        ));
    }
}
