<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $clientId = $request->get('client_id');

        // --- Calculate Previous Period for Trend Calculations ---
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $daysDiff = $start->diffInDays($end) + 1;

        $prevStartDate = $start->copy()->subDays($daysDiff)->toDateString();
        $prevEndDate = $start->copy()->subDay()->toDateString();

        // --- Invoice Reports ---
        $invoiceQuery = Invoice::whereBetween('tanggal_invoice', [$startDate, $endDate]);
        if ($clientId) {
            $invoiceQuery->where('client_id', $clientId);
        }

        $invoiceStats = [
            'total_count' => (clone $invoiceQuery)->count(),
            'total_value' => (clone $invoiceQuery)->sum('total'),
            'status_breakdown' => (clone $invoiceQuery)
                ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
                ->groupBy('status')
                ->get(),
        ];

        // --- Previous Invoice Stats for Growth ---
        $prevInvoiceQuery = Invoice::whereBetween('tanggal_invoice', [$prevStartDate, $prevEndDate]);
        if ($clientId) {
            $prevInvoiceQuery->where('client_id', $clientId);
        }
        $prevInvoiceCount = $prevInvoiceQuery->count();
        $prevInvoiceValue = $prevInvoiceQuery->sum('total');

        $invoiceStats['count_growth'] = $prevInvoiceCount > 0 
            ? (($invoiceStats['total_count'] - $prevInvoiceCount) / $prevInvoiceCount) * 100 
            : 0;
            
        $invoiceStats['value_growth'] = $prevInvoiceValue > 0 
            ? (($invoiceStats['total_value'] - $prevInvoiceValue) / $prevInvoiceValue) * 100 
            : 0;

        // --- Receipt (Payment) Reports ---
        $paymentQuery = Payment::whereBetween('payment_date', [$startDate, $endDate]);
        if ($clientId) {
            $paymentQuery->whereHas('invoice', function($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $paymentStats = [
            'total_collected' => (clone $paymentQuery)->sum('amount'),
            'method_breakdown' => (clone $paymentQuery)
                ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
                ->groupBy('payment_method')
                ->get(),
            'recent_payments' => (clone $paymentQuery)->with(['invoice.client'])->latest('payment_date')->take(10)->get(),
        ];

        // --- Previous Payment Stats for Growth ---
        $prevPaymentQuery = Payment::whereBetween('payment_date', [$prevStartDate, $prevEndDate]);
        if ($clientId) {
            $prevPaymentQuery->whereHas('invoice', function($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }
        $prevCollected = $prevPaymentQuery->sum('amount');
        $paymentStats['collected_growth'] = $prevCollected > 0
            ? (($paymentStats['total_collected'] - $prevCollected) / $prevCollected) * 100
            : 0;

        // --- Outstanding Balance (Active Filter Range) ---
        $outstandingQuery = Invoice::whereBetween('tanggal_invoice', [$startDate, $endDate])
            ->whereIn('status', ['sent', 'dp', 'pending', 'overdue']);
        if ($clientId) {
            $outstandingQuery->where('client_id', $clientId);
        }
        $totalOutstanding = $outstandingQuery->sum(DB::raw('total - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)'));

        // --- Previous Outstanding Balance for Growth ---
        $prevOutstandingQuery = Invoice::whereBetween('tanggal_invoice', [$prevStartDate, $prevEndDate])
            ->whereIn('status', ['sent', 'dp', 'pending', 'overdue']);
        if ($clientId) {
            $prevOutstandingQuery->where('client_id', $clientId);
        }
        $prevOutstanding = $prevOutstandingQuery->sum(DB::raw('total - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)'));
        $outstandingGrowth = $prevOutstanding > 0
            ? (($totalOutstanding - $prevOutstanding) / $prevOutstanding) * 100
            : 0;

        // --- Recent Transaction Logs (Invoices) ---
        $recentInvoicesQuery = Invoice::with('client');
        if ($clientId) {
            $recentInvoicesQuery->where('client_id', $clientId);
        }
        $recentInvoices = $recentInvoicesQuery->latest('updated_at')->take(10)->get();

        // --- Monthly Trend Data ---
        $trendMonths = [];
        $trendRevenue = [];
        $trendReceivables = [];

        // Dynamic date format string depending on connection driver to avoid SQLite incompatibility
        $driver = DB::connection()->getDriverName();
        $dateGroupRaw = $driver === 'sqlite'
            ? "strftime('%Y-%m', tanggal_invoice) as month"
            : "DATE_FORMAT(tanggal_invoice, '%Y-%m') as month";

        $monthlyStats = Invoice::select(
            DB::raw($dateGroupRaw),
            DB::raw("SUM(CASE WHEN status = 'paid' THEN total ELSE 0 END) as revenue"),
            DB::raw("SUM(CASE WHEN status != 'paid' THEN total ELSE 0 END) as receivables")
        )
        ->whereBetween('tanggal_invoice', [$startDate, $endDate]);
        
        if ($clientId) {
            $monthlyStats->where('client_id', $clientId);
        }
        
        $monthlyStats = $monthlyStats->groupBy('month')
            ->orderBy('month')
            ->get();

        foreach ($monthlyStats as $stat) {
            $trendMonths[] = Carbon::parse($stat->month . '-01')->format('M Y');
            $trendRevenue[] = (float)$stat->revenue;
            $trendReceivables[] = (float)$stat->receivables;
        }

        // Fallback to last 6 months if date range has no transactions
        if (empty($trendMonths)) {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $trendMonths[] = $month->format('M Y');
                
                $mStart = $month->copy()->startOfMonth()->toDateString();
                $mEnd = $month->copy()->endOfMonth()->toDateString();
                
                $mQuery = Invoice::whereBetween('tanggal_invoice', [$mStart, $mEnd]);
                if ($clientId) {
                    $mQuery->where('client_id', $clientId);
                }
                
                $trendRevenue[] = (float)(clone $mQuery)->where('status', 'paid')->sum('total');
                $trendReceivables[] = (float)(clone $mQuery)->whereIn('status', ['sent', 'dp', 'pending', 'overdue'])->sum('total');
            }
        }

        // --- Client Analytics & Trends ---
        // 1. Highest Revenue Drivers
        $clientRevenueQuery = Client::select('clients.id', 'clients.nama_client', 'clients.nama_perusahaan', DB::raw('SUM(invoices.total) as total_revenue'))
            ->join('invoices', 'invoices.client_id', '=', 'clients.id')
            ->where('invoices.status', 'paid')
            ->whereBetween('invoices.tanggal_invoice', [$startDate, $endDate]);
        if ($clientId) {
            $clientRevenueQuery->where('clients.id', $clientId);
        }
        $topClientRevenue = $clientRevenueQuery->groupBy('clients.id', 'clients.nama_client', 'clients.nama_perusahaan')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // 2. Highest Outstanding Debts (Payment Delays)
        $clientOutstandingQuery = Client::select('clients.id', 'clients.nama_client', 'clients.nama_perusahaan', DB::raw("SUM(invoices.total - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) as total_outstanding"))
            ->join('invoices', 'invoices.client_id', '=', 'clients.id')
            ->whereIn('invoices.status', ['sent', 'dp', 'pending', 'overdue'])
            ->whereBetween('invoices.tanggal_invoice', [$startDate, $endDate]);
        if ($clientId) {
            $clientOutstandingQuery->where('clients.id', $clientId);
        }
        $topClientOutstanding = $clientOutstandingQuery->groupBy('clients.id', 'clients.nama_client', 'clients.nama_perusahaan')
            ->orderByDesc('total_outstanding')
            ->take(5)
            ->get();

        $clients = Client::orderBy('nama_client')->get();

        return view('reports.index', compact(
            'startDate', 'endDate', 'clientId', 'clients',
            'invoiceStats', 'paymentStats', 'totalOutstanding', 'outstandingGrowth', 'recentInvoices',
            'trendMonths', 'trendRevenue', 'trendReceivables',
            'topClientRevenue', 'topClientOutstanding'
        ));
    }
}
