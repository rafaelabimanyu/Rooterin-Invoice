<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::where('status', 'aktif')->count();
        $totalInvoices = Invoice::count();
        $paidInvoicesCount = Invoice::where('status', 'paid')->count();
        $pendingInvoicesCount = Invoice::whereIn('status', ['sent', 'pending', 'dp'])->count();
        
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        $pendingRevenue = Invoice::whereIn('status', ['sent', 'pending', 'dp'])->sum('total');
        
        $totalReceipts = \App\Models\Receipt::count();
        $pendingReceipts = 0;

        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        $overdueRevenue = Invoice::whereIn('status', ['sent', 'pending', 'dp'])
            ->where('due_date', '<', Carbon::now())
            ->sum('total');

        // Compile 3 months trend
        $threeMonthsAgo = Carbon::now()->subMonths(2)->startOfMonth();
        $recentThreeMonthsInvoices = Invoice::where('created_at', '>=', $threeMonthsAgo)->get();

        $trendSummary = [];
        for ($i = 2; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthTotal = $recentThreeMonthsInvoices->filter(function($invoice) use ($monthDate) {
                return $invoice->created_at && $invoice->created_at->format('Y-m') === $monthDate->format('Y-m');
            })->sum('total');
            
            $trendSummary[] = $monthDate->format('F Y') . ": Rp " . number_format($monthTotal, 0, ',', '.');
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
            $todayReceiptsCount = \App\Models\Receipt::where('created_by', auth()->id())
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
                ->whereIn('action', ['login', 'create_invoice', 'update_invoice'])
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

            // Dynamic Cash Flow Data (Last 6 Months)
            $rawCashFlow = [];
            $maxVal = 0;
            for ($i = 5; $i >= 0; $i--) {
                $monthDate = Carbon::now()->subMonths($i);
                
                $revenue = (float) \App\Models\Receipt::whereMonth('payment_date', $monthDate->month)
                    ->whereYear('payment_date', $monthDate->year)
                    ->sum('amount_received');
                    
                $receivables = (float) Invoice::whereMonth('created_at', $monthDate->month)
                    ->whereYear('created_at', $monthDate->year)
                    ->where('status', '!=', 'paid')
                    ->sum('total');
                    
                $maxVal = max($maxVal, $revenue, $receivables);
                
                $rawCashFlow[] = [
                    'date' => $monthDate,
                    'revenue' => $revenue,
                    'receivables' => $receivables,
                ];
            }
            
            if ($maxVal <= 0) {
                $maxVal = 1;
            }
            
            $locale = app()->getLocale();
            $monthNamesId = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
                7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
            ];
            $monthNamesEn = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
                7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
            ];
            
            foreach ($rawCashFlow as $item) {
                $monthNum = $item['date']->month;
                $label = $locale === 'id' ? $monthNamesId[$monthNum] : $monthNamesEn[$monthNum];
                
                $cashFlowData[] = [
                    'month_label' => $label,
                    'revenue_height' => round(($item['revenue'] / $maxVal) * 100),
                    'receivables_height' => round(($item['receivables'] / $maxVal) * 100),
                    'revenue_formatted' => $this->formatChartAmount($item['revenue'], $locale),
                    'receivables_formatted' => $this->formatChartAmount($item['receivables'], $locale),
                ];
            }
 
            // A. TOP CLIENTS BY REVENUE
            $topClients = Client::whereHas('invoices', function ($query) {
                    $query->where('status', 'paid');
                })
                ->withSum(['invoices' => function ($query) {
                    $query->where('status', 'paid');
                }], 'total')
                ->orderByDesc('invoices_sum_total')
                ->take(5)
                ->get();
 
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
        }

        $businessUnitSummary = \App\Models\BusinessUnit::withCount(['invoices as total_orders'])
            ->withSum(['invoices as total_revenue' => function($query) {
                $query->where('status', 'paid');
            }], 'total')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function ($unit) {
                $unit->total_revenue = $unit->total_revenue ?? 0;
                return $unit;
            });
        
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
            'businessUnitSummary'
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
