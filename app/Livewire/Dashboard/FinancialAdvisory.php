<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinancialAdvisory extends Component
{
    public $aiInsight = '';
    public $locale = '';

    public function mount()
    {
        $this->locale = app()->getLocale();
        $this->loadInsight();
    }

    public function refreshAnalysis()
    {
        Cache::forget('ai_financial_insights_' . $this->locale);
        $this->loadInsight();
    }

    protected function loadInsight()
    {
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

        $locale = $this->locale;

        $this->aiInsight = Cache::remember('ai_financial_insights_' . $locale, 3600, function() use ($monthlyRevenue, $overdueRevenue, $trendText, $locale) {
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
                
                $response = Http::withHeaders([
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
                Log::error("DashboardController Gemini Error: " . $e->getMessage(), ['exception' => $e]);
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

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Placeholder skeleton during Lazy Loading -->
            <div class="mb-12 page-fade-in stagger-1">
                <div class="bg-gradient-to-r from-gold-50/50 to-slate-50/30 rounded-3xl border border-gold-100/80 p-6 md:p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden animate-pulse">
                    <div class="flex items-start gap-4 sm:gap-5 w-full">
                        <div class="w-12 h-12 rounded-2xl bg-gold-200/50 shrink-0 shadow-sm border border-gold-200/30"></div>
                        <div class="space-y-3 flex-grow">
                            <div class="h-4 bg-gold-200/50 rounded w-1/4"></div>
                            <div class="h-6 bg-slate-200/50 rounded w-1/3"></div>
                            <div class="h-4 bg-slate-200/50 rounded w-2/3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.dashboard.financial-advisory');
    }
}
