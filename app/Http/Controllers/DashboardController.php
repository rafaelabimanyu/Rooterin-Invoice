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
        $pendingReceipts = \App\Models\Receipt::where('status', 'sent')->count();

        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('tanggal_invoice', Carbon::now()->month)
            ->whereYear('tanggal_invoice', Carbon::now()->year)
            ->sum('total');

        $overdueRevenue = Invoice::whereIn('status', ['sent', 'pending', 'dp'])
            ->where('due_date', '<', Carbon::now())
            ->sum('total');

        // Compile 3 months trend
        $threeMonthsAgo = Carbon::now()->subMonths(2)->startOfMonth();
        $recentThreeMonthsInvoices = Invoice::where('tanggal_invoice', '>=', $threeMonthsAgo)->get();

        $trendSummary = [];
        for ($i = 2; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthTotal = $recentThreeMonthsInvoices->filter(function($invoice) use ($monthDate) {
                return $invoice->tanggal_invoice && $invoice->tanggal_invoice->format('Y-m') === $monthDate->format('Y-m');
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

        $aiInsight = null;
        if (!$isStaff) {
            $locale = app()->getLocale();
            if (request()->has('refresh_ai')) {
                \Illuminate\Support\Facades\Cache::forget('ai_financial_insights_' . $locale);
            }

            $aiInsight = \Illuminate\Support\Facades\Cache::remember('ai_financial_insights_' . $locale, 3600, function() use ($monthlyRevenue, $overdueRevenue, $trendText, $locale) {
                if ($locale === 'en') {
                    $prompt = "You are a professional business financial consultant. Analyze the following financial summary smartly and tactfully:
- Total Invoices Paid This Month: Rp " . number_format($monthlyRevenue, 0, ',', '.') . "
- Total Overdue Invoices: Rp " . number_format($overdueRevenue, 0, ',', '.') . "
- Sales/Invoice Trend for the Last 3 Months: {$trendText}

Strictly match the user's current application language interface. If the active language is 'en', you MUST construct your entire analysis, greetings, and responses in Professional English. If the active language is 'id', you MUST respond in Professional Indonesian. Never mix the languages.
Provide 2-3 sentences containing tactical business insights and practical action recommendations to help the business owner maintain smooth cash flow. Do not use any markdown formatting (like bold/italic or lists), return only plain text paragraph.";
                } else {
                    $prompt = "Kamu adalah konsultan keuangan bisnis terpercaya. Analisis data ringkasan keuangan berikut secara cerdas dan taktis:
- Total Invoice Lunas Bulan Ini: Rp " . number_format($monthlyRevenue, 0, ',', '.') . "
- Total Tagihan Menunggak (Overdue): Rp " . number_format($overdueRevenue, 0, ',', '.') . "
- Tren Penjualan/Invoice 3 Bulan Terakhir: {$trendText}

Strictly match the user's current application language interface. If the active language is 'en', you MUST construct your entire analysis, greetings, and responses in Professional English. If the active language is 'id', you MUST respond in Professional Indonesian. Never mix the languages.
Berikan 2-3 kalimat berisi insight bisnis taktis dan rekomendasi tindakan praktis dalam Bahasa Indonesia yang profesional untuk membantu pemilik bisnis menjaga kelancaran cash flow (arus kas). Jangan gunakan format markdown (seperti tebal/miring atau list), kembalikan langsung paragraf teks bersih.";
                }

                try {
                    $apiKey = env('GEMINI_API_KEY') ?: config('gemini.api_key');
                    if (empty($apiKey)) {
                        throw new \Exception("GEMINI_API_KEY tidak dikonfigurasi");
                    }
                    
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ]
                    ]);

                    if (!$response->successful()) {
                        throw new \Exception("HTTP Error: Status " . $response->status());
                    }

                    $resData = $response->json();
                    $reply = $resData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    
                    if (empty($reply)) {
                        throw new \Exception("Response format invalid");
                    }

                    return trim($reply);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("DashboardController Gemini Error: " . $e->getMessage(), ['exception' => $e]);
                    $errMsg = " (Advisory Fallback)";
                    $overdueFormatted = "Rp " . number_format($overdueRevenue, 0, ',', '.');
                    if ($locale === 'en') {
                        if ($overdueRevenue > 0) {
                            return "Your total active arrears are currently at {$overdueFormatted}. We recommend prioritizing collection efforts on major corporate and government entities (e.g., Dinas Kesehatan or Yayasan Pendidikan) using AI Billing Copywriter to stabilize operational cash flow." . $errMsg;
                        } else {
                            return "Your financial performance this month is stable with a 100% invoice collection rate. Continue to monitor incoming billings closely to sustain this cash flow efficiency." . $errMsg;
                        }
                    } else {
                        if ($overdueRevenue > 0) {
                            return "Total tagihan menunggak (piutang aktif) saat ini mencapai {$overdueFormatted}. Direkomendasikan untuk memprioritaskan penagihan aktif pada klien besar seperti Dinas Kesehatan Kota atau Yayasan Pendidikan dengan mengirimkan email pengingat menggunakan AI Billing Copywriter agar arus kas tetap stabil." . $errMsg;
                        } else {
                            return "Performa keuangan Anda bulan ini sangat sehat dengan tingkat kolektibilitas piutang 100%. Tetap pantau penagihan baru secara berkala untuk menjaga efisiensi arus kas operasional." . $errMsg;
                        }
                    }
                }
            });
        }
        
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
                        preg_match('/ROOT-INV-\d+/i', $log->description, $matches);
                        $invNum = $matches[0] ?? 'ROOT-INV-XXXX';
                        
                        $details_key = 'created_invoice';
                        $details_params = ['inv' => $invNum];
                    } elseif ($log->action === 'update_invoice' || $log->action === 'updated_invoice') {
                        $action = 'invoice_updated';
                        preg_match('/ROOT-INV-\d+/i', $log->description, $matches);
                        $invNum = $matches[0] ?? 'ROOT-INV-XXXX';
                        
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
                
                $revenue = (float) \App\Models\Payment::whereMonth('payment_date', $monthDate->month)
                    ->whereYear('payment_date', $monthDate->year)
                    ->sum('amount');
                    
                $receivables = (float) Invoice::whereMonth('tanggal_invoice', $monthDate->month)
                    ->whereYear('tanggal_invoice', $monthDate->year)
                    ->where('status', '!=', 'paid')
                    ->get()
                    ->sum(fn($inv) => $inv->total - $inv->payments->sum('amount'));
                    
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
                ->with('payments')
                ->get();

            $today = Carbon::today();
            foreach ($unpaidInvoices as $invoice) {
                $amountDue = $invoice->total - $invoice->payments->sum('amount');
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
            'aiInsight',
            'topClients',
            'invoiceAgeing'
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
