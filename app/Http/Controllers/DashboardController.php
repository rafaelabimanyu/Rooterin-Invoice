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
            'aiInsight'
        ));
    }
}
