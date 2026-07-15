<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Services\BusinessUnitReportingService;
use App\Services\PredictiveInsightService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $reportingService;

    public function __construct(BusinessUnitReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function index(PredictiveInsightService $insightService)
    {
        $totalClients = Client::where('status', 'aktif')->count();
        $overdueClients = collect();
        $paymentMethodsBreakdown = collect();
        $recentPayments = collect();
        $totalPaymentsAmount = 0;
        $averagePaymentAmount = 0;
        
        $globalStats = $this->reportingService->getSummaryStats();
        $totalInvoices = $globalStats['total_invoices_count'];
        $paidInvoicesCount = $globalStats['paid_invoices_count'];
        $pendingInvoicesCount = $totalInvoices - $paidInvoicesCount;
        
        $totalRevenue = $globalStats['total_revenue'];
        $pendingRevenue = $globalStats['total_outstanding'];
        
        $totalReceipts = \App\Models\Receipt::count();
        $pendingReceipts = 0;

        $monthlyStats = $this->reportingService->getSummaryStats([
            'start_date' => Carbon::now()->startOfMonth()->toDateString(),
            'end_date' => Carbon::now()->endOfMonth()->toDateString(),
        ]);
        $monthlyRevenue = $monthlyStats['total_revenue'];

        $overdueRevenue = $globalStats['overdue_outstanding'];

        // Compile 3 months trend
        $threeMonthsTrend = $this->reportingService->getMonthlyTrend([], 3);
        $trendSummary = [];
        foreach ($threeMonthsTrend as $t) {
            $trendSummary[] = $t['month_label'] . ": Rp " . number_format($t['total_billed'], 0, ',', '.');
        }
        $trendText = implode(', ', $trendSummary);

        $isStaff = auth()->user()->role === 'staff';
        $securityLogs = collect();
        $cashFlowData = [];
        $topClients = collect();
        $invoiceAgeing = [
            'current' => 0,
            'overdue_1_30' => 0,
            'overdue_31_60' => 0,
            'overdue_60_plus' => 0,
        ];

        // AI Insight was refactored into a lazy-loaded Livewire component
        
        if ($isStaff) {
            $todayInvoicesCount = Invoice::where('created_by', auth()->id())
                ->where('created_at', '>=', now()->startOfDay())
                ->count();
            $todayReceiptsCount = \App\Models\Receipt::whereHas('invoice', function ($q) {
                $q->where('created_by', auth()->id());
            })
                ->where('created_at', '>=', now()->startOfDay())
                ->count();
            $todayRevenue = Invoice::where('created_by', auth()->id())
                ->where('created_at', '>=', now()->startOfDay())
                ->sum('total');
            
            $recentInvoices = Invoice::with('client')
                ->where('created_by', auth()->id())
                ->latest()
                ->take(5)
                ->get();

            // New Staff Features
            $dailyGoal = 5; // Example goal
            $goalProgress = min(100, round(($todayInvoicesCount / $dailyGoal) * 100));
            
            $quotes = [
                "Quality is not an act, it is a habit.",
                "Success is the sum of small efforts, repeated day-in and day-out.",
                "Your work is going to fill a large part of your life.",
                "Don't count the days, make the days count.",
                "Efficiency is doing things right; effectiveness is doing the right things."
            ];
            $randomQuote = $quotes[array_rand($quotes)];

            $activityLogs = \App\Models\ActivityLog::where('user_id', auth()->id())
                ->where('created_at', '>=', now()->startOfDay())
                ->latest()
                ->take(5)
                ->get();
        } else {
            $recentInvoices = Invoice::with('client')->latest()->take(5)->get();
            $todayInvoicesCount = Invoice::where('created_at', '>=', now()->startOfDay())->count();
            $todayReceiptsCount = \App\Models\Receipt::where('created_at', '>=', now()->startOfDay())->count();
            $todayRevenue = Invoice::where('status', 'paid')
                ->where('created_at', '>=', now()->startOfDay())
                ->sum('total');
            
            $dailyGoal = null;
            $goalProgress = null;
            $randomQuote = null;
            $activityLogs = collect();

            // Dynamic Security Logs for Admin/Owner Dashboard
            $rawSecurityLogs = \App\Models\SecurityLog::with('user')
                ->latest()
                ->take(10)
                ->get();

            $rawActivityLogs = \App\Models\ActivityLog::with('user')
                ->whereIn('action', ['login', 'create_invoice', 'update_invoice', 'record_payment', 'delete_payment'])
                ->latest()
                ->take(10)
                ->get();

            $mergedLogs = collect()
                ->merge($rawSecurityLogs)
                ->merge($rawActivityLogs)
                ->sortByDesc('created_at')
                ->take(6);

            $securityLogs = $mergedLogs->map(function ($log) {
                $time = $log->created_at;
                $user = $log->user ? $log->user->name : 'System Monitor';
                $role = $log->user ? ucfirst($log->user->role) : 'Security';
                
                if ($log instanceof \App\Models\SecurityLog) {
                    $isSuspicious = $log->is_suspicious;
                    if ($isSuspicious) {
                        $action = 'security_alert';
                        $type = 'danger';
                        
                        if (stripos($log->activity, 'failed') !== false) {
                            $details_key = 'failed_admin_login';
                            $details_params = ['ip' => $log->ip_address];
                        } else {
                            $details_key = 'high_api_rate';
                            $details_params = ['node' => $log->location ?: 'Node-02'];
                        }
                    } else {
                        $action = 'success_login';
                        $type = 'success';
                        $details_key = 'logged_in_ip';
                        $details_params = ['ip' => $log->ip_address];
                    }
                } else {
                    $type = 'info';
                    
                    if ($log->action === 'create_invoice' || $log->action === 'created_invoice') {
                        $action = 'invoice_created';
                        preg_match('/(INV-\d+-\d+|[A-Z0-9-]+)/i', $log->description, $matches);
                        $invNum = $matches[0] ?? 'INV-XXXX-YYYY';
                        
                        $details_key = 'created_invoice';
                        $details_params = ['inv' => $invNum];
                    } elseif ($log->action === 'record_payment') {
                        $action = 'payment_recorded';
                        $details_key = 'recorded_payment';
                        $details_params = ['msg' => $log->description];
                    } elseif ($log->action === 'delete_payment') {
                        $action = 'payment_deleted';
                        $details_key = 'deleted_payment';
                        $details_params = ['msg' => $log->description];
                    } elseif ($log->action === 'update_invoice' || $log->action === 'updated_invoice') {
                        $action = 'invoice_updated';
                        preg_match('/(INV-\d+-\d+|[A-Z0-9-]+)/i', $log->description, $matches);
                        $invNum = $matches[0] ?? 'INV-XXXX-YYYY';
                        
                        $clientName = 'Klien';
                        if ($log->model_type === 'App\Models\Invoice') {
                            $inv = \App\Models\Invoice::with('client')->find($log->model_id);
                            if ($inv && $inv->client) {
                                $clientName = $inv->client->nama_perusahaan ?: $inv->client->nama_client;
                            }
                        }
                        
                        $details_key = 'updated_invoice';
                        $details_params = ['inv' => $invNum, 'client' => $clientName];
                    } else {
                        $action = 'invoice_created';
                        $details_key = 'created_invoice';
                        $details_params = ['inv' => $log->description];
                    }
                }
                
                return [
                    'time' => $time,
                    'user' => $user,
                    'role' => $role,
                    'action' => $action,
                    'details_key' => $details_key,
                    'details_params' => $details_params,
                    'type' => $type
                ];
            });

            // Dynamic Cash Flow Data (Last 3 Months) using BusinessUnitReportingService
            $monthlyTrend = $this->reportingService->getMonthlyTrend([], 3);
            $maxVal = collect($monthlyTrend)->max(fn($t) => max($t['revenue'], $t['receivables'])) ?: 1;
            if ($maxVal <= 0) {
                $maxVal = 1;
            }
            
            $locale = app()->getLocale();
            $cashFlowData = [];
            foreach ($monthlyTrend as $item) {
                $cashFlowData[] = [
                    'month_label' => explode(' ', $item['month_label'])[0],
                    'revenue_height' => round(($item['revenue'] / $maxVal) * 100),
                    'receivables_height' => round(($item['receivables'] / $maxVal) * 100),
                    'revenue_formatted' => $this->formatChartAmount($item['revenue'], $locale),
                    'receivables_formatted' => $this->formatChartAmount($item['receivables'], $locale),
                ];
            }
 
            // A. TOP CLIENTS BY REVENUE
            $topClients = $this->reportingService->getTopClients([], 5);
 
            // B. INVOICE AGEING SUMMARY
            $unpaidInvoices = Invoice::whereIn('status', ['sent', 'pending', 'dp', 'overdue'])
                ->get();
 
            $today = Carbon::today();
            foreach ($unpaidInvoices as $invoice) {
                $amountDue = $invoice->total;
                if ($amountDue <= 0) {
                    continue;
                }
                
                $dueDate = Carbon::parse($invoice->due_date);
                if ($dueDate->greaterThanOrEqualTo($today)) {
                    $invoiceAgeing['current'] += $amountDue;
                } else {
                    $daysOverdue = $today->diffInDays($dueDate);
                    if ($daysOverdue <= 30) {
                        $invoiceAgeing['overdue_1_30'] += $amountDue;
                    } elseif ($daysOverdue <= 60) {
                        $invoiceAgeing['overdue_31_60'] += $amountDue;
                    } else {
                        $invoiceAgeing['overdue_60_plus'] += $amountDue;
                    }
                }
            }

            // Top 3 Overdue Clients by unpaid overdue amount
            $overdueClients = Client::whereHas('invoices', function ($q) {
                $q->whereIn('status', ['sent', 'pending', 'dp', 'overdue'])
                  ->where('due_date', '<', Carbon::now()->toDateString());
            })
            ->withSum(['invoices' => function ($q) {
                $q->whereIn('status', ['sent', 'pending', 'dp', 'overdue'])
                  ->where('due_date', '<', Carbon::now()->toDateString());
            }], 'total')
            ->orderByDesc('invoices_sum_total')
            ->take(3)
            ->get();

            // Payment analytics
            $paymentMethodsBreakdown = \App\Models\Payment::select('payment_method', \DB::raw('count(*) as count'), \DB::raw('sum(amount) as total_amount'))
                ->groupBy('payment_method')
                ->orderByDesc('count')
                ->get();
            
            $totalPaymentsAmount = \App\Models\Payment::sum('amount');
            $totalPaymentsCount = \App\Models\Payment::count();
            $averagePaymentAmount = $totalPaymentsCount > 0 ? ($totalPaymentsAmount / $totalPaymentsCount) : 0;

            $recentPayments = \App\Models\Payment::with('invoice.client')
                ->latest('payment_date')
                ->latest('id')
                ->take(5)
                ->get();
        }
        $insights = !$isStaff ? $insightService->generateInsights() : [];

        $businessUnitSummary = $this->reportingService->getBusinessUnitsSummary();
        
        return view('dashboard', compact(
            'totalClients', 
            'totalInvoices', 
            'paidInvoicesCount', 
            'pendingInvoicesCount',
            'totalRevenue',
            'pendingRevenue',
            'monthlyRevenue',
            'recentInvoices',
            'totalReceipts',
            'pendingReceipts',
            'isStaff',
            'todayInvoicesCount',
            'todayReceiptsCount',
            'todayRevenue',
            'dailyGoal',
            'goalProgress',
            'randomQuote',
            'activityLogs',
            'securityLogs',
            'cashFlowData',
            'topClients',
            'invoiceAgeing',
            'businessUnitSummary',
            'insights',
            'overdueClients',
            'paymentMethodsBreakdown',
            'recentPayments',
            'totalPaymentsAmount',
            'averagePaymentAmount'
        ));
    }

    private function formatChartAmount($amount, $locale)
    {
        if ($amount >= 1000000000) {
            return 'Rp ' . number_format($amount / 1000000000, 1, ',', '.') . ($locale == 'en' ? 'B' : ' Miliar');
        } elseif ($amount >= 1000000) {
            return 'Rp ' . number_format($amount / 1000000, 1, ',', '.') . ($locale == 'en' ? 'M' : ' Juta');
        } elseif ($amount >= 1000) {
            return 'Rp ' . number_format($amount / 1000, 1, ',', '.') . ($locale == 'en' ? 'K' : ' Ribu');
        } else {
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }
    }
}
