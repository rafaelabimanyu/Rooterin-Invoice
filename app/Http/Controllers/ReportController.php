<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Services\BusinessUnitReportingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesReportExport;
use App\Exports\ReceiptsReportExport;
use App\Exports\ClientsReportExport;

class ReportController extends Controller
{
    protected $reportingService;

    public function __construct(BusinessUnitReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }
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

        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'client_id' => $clientId,
        ];
        $stats = $this->reportingService->getSummaryStats($filters);

        $prevFilters = [
            'start_date' => $prevStartDate,
            'end_date' => $prevEndDate,
            'client_id' => $clientId,
        ];
        $prevStats = $this->reportingService->getSummaryStats($prevFilters);

        // --- Invoice Reports ---
        $invoiceStats = [
            'total_count' => $stats['total_invoices_count'],
            'total_value' => $stats['total_billed'],
            'status_breakdown' => $stats['status_breakdown'],
            'count_growth' => $prevStats['total_invoices_count'] > 0 
                ? (($stats['total_invoices_count'] - $prevStats['total_invoices_count']) / $prevStats['total_invoices_count']) * 100 
                : 0,
            'value_growth' => $prevStats['total_billed'] > 0 
                ? (($stats['total_billed'] - $prevStats['total_billed']) / $prevStats['total_billed']) * 100 
                : 0,
        ];

        // --- Receipt (Payment) Reports ---
        $paymentQuery = Receipt::whereBetween('payment_date', [$startDate, $endDate]);
        if ($clientId) {
            $paymentQuery->whereHas('invoice', function($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $paymentStats = [
            'total_collected' => (clone $paymentQuery)->sum('amount_received'),
            'method_breakdown' => collect([]),
            'recent_payments' => (clone $paymentQuery)->with(['invoice.client'])->latest('payment_date')->take(10)->get(),
        ];

        // --- Previous Payment Stats for Growth ---
        $prevPaymentQuery = Receipt::whereBetween('payment_date', [$prevStartDate, $prevEndDate]);
        if ($clientId) {
            $prevPaymentQuery->whereHas('invoice', function($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }
        $prevCollected = $prevPaymentQuery->sum('amount_received');
        $paymentStats['collected_growth'] = $prevCollected > 0
            ? (($paymentStats['total_collected'] - $prevCollected) / $prevCollected) * 100
            : 0;

        // --- Outstanding Balance (Active Filter Range) ---
        $totalOutstanding = $stats['total_outstanding'];

        // --- Previous Outstanding Balance for Growth ---
        $prevOutstanding = $prevStats['total_outstanding'];
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

        $monthlyTrend = $this->reportingService->getMonthlyTrend($filters);
        foreach ($monthlyTrend as $item) {
            $trendMonths[] = $item['month_label'];
            $trendRevenue[] = $item['revenue'];
            $trendReceivables[] = $item['receivables'];
        }

        // Fallback to last 6 months if date range has no transactions
        if (empty($trendMonths)) {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $trendMonths[] = $month->format('M Y');
                
                $mStart = $month->copy()->startOfMonth()->toDateString();
                $mEnd = $month->copy()->endOfMonth()->toDateString();
                
                $mQuery = Invoice::whereBetween('created_at', [$mStart, $mEnd]);
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
            ->whereBetween('invoices.created_at', [$startDate, $endDate]);
        if ($clientId) {
            $clientRevenueQuery->where('clients.id', $clientId);
        }
        $topClientRevenue = $clientRevenueQuery->groupBy('clients.id', 'clients.nama_client', 'clients.nama_perusahaan')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // 2. Highest Outstanding Debts (Payment Delays)
        $clientOutstandingQuery = Client::select('clients.id', 'clients.nama_client', 'clients.nama_perusahaan', DB::raw("SUM(invoices.total - COALESCE((SELECT SUM(amount_received) FROM receipts WHERE receipts.invoice_id = invoices.id), 0)) as total_outstanding"))
            ->join('invoices', 'invoices.client_id', '=', 'clients.id')
            ->whereIn('invoices.status', ['sent', 'dp', 'pending', 'overdue'])
            ->whereBetween('invoices.created_at', [$startDate, $endDate]);
        if ($clientId) {
            $clientOutstandingQuery->where('clients.id', $clientId);
        }
        $topClientOutstanding = $clientOutstandingQuery->groupBy('clients.id', 'clients.nama_client', 'clients.nama_perusahaan')
            ->orderByDesc('total_outstanding')
            ->take(5)
            ->get();

        $clients = Client::orderBy('nama_client')->get();

        // --- Business Units Summary (Profit Sharing) ---
        $businessUnitStats = $this->reportingService->getBusinessUnitsSummary($filters);

        return view('reports.index', compact(
            'startDate', 'endDate', 'clientId', 'clients',
            'invoiceStats', 'paymentStats', 'totalOutstanding', 'outstandingGrowth', 'recentInvoices',
            'trendMonths', 'trendRevenue', 'trendReceivables',
            'topClientRevenue', 'topClientOutstanding', 'businessUnitStats'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $clientId = $request->get('client_id');
        $tab = $request->get('tab', 'invoices');

        switch ($tab) {
            case 'receipts':
                $export = new ReceiptsReportExport($startDate, $endDate, $clientId);
                $filename = "Laporan_Kuitansi_Pembayaran_{$startDate}_to_{$endDate}.xlsx";
                break;
            case 'clients':
                $export = new ClientsReportExport($startDate, $endDate, $clientId);
                $filename = "Laporan_Analisis_Klien_{$startDate}_to_{$endDate}.xlsx";
                break;
            case 'invoices':
            default:
                $export = new InvoicesReportExport($startDate, $endDate, $clientId);
                $filename = "Laporan_Kinerja_Faktur_{$startDate}_to_{$endDate}.xlsx";
                break;
        }

        return Excel::download($export, $filename);
    }
}
