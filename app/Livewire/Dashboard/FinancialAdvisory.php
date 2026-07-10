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
        $this->aiInsight = $this->locale == 'en' ? 'Processing the latest data...' : 'Sedang mengolah data terbaru...';
        $this->loadInsight(true);
    }

    protected function loadInsight($force = false)
    {
        $aggregator = app(\App\Services\DataAggregatorService::class);
        $overview = $aggregator->getFinancialOverview($this->locale);

        $monthlyRevenue = $overview['monthly_revenue'];
        $overdueRevenue = $overview['overdue_revenue'];
        $collectionRate = $overview['collection_rate'];
        $trendText = $overview['trend_text'];

        $locale = $this->locale;

        $fetchCallback = function() use ($monthlyRevenue, $overdueRevenue, $collectionRate, $trendText, $locale) {
            $formattedMonthly = "Rp " . number_format($monthlyRevenue, 0, ',', '.');
            $formattedOverdue = "Rp " . number_format($overdueRevenue, 0, ',', '.');
            $formattedCollectionRate = number_format($collectionRate, 1, ',', '.') . "%";
            
            if ($locale === 'en') {
                $prompt = "You are a strategic Executive Business Partner for J&J GROUP. You provide to-the-point, sharp, analytical financial advisory free of conversational fluff.
Analyze the following data:
- Total Invoices Paid This Month: {$formattedMonthly}
- Total Overdue Invoices: {$formattedOverdue}
- Invoice Collection Rate: {$formattedCollectionRate}
- Sales/Invoice Trend for the Last 3 Months: {$trendText}

You MUST format your response strictly using this exact structure:
[Analisis Data]
(Brief analysis based on real figures)

[Dampak Bisnis]
(Direct impact/causes on J&J GROUP receivables or cash flow)

[Rekomendasi Aksi]
(Specific, concrete, and immediately executable action recommendations)

Rules:
- Use professional, firm, and concise English.
- Do NOT use other markdown formatting (such as bold/italic asterisks, headers, or bullet lists). Return clean paragraphs separated strictly by the section tags above.";
            } else {
                $prompt = "Anda adalah Executive Business Partner strategis khusus untuk J&J GROUP. Anda memberikan nasihat finansial yang to-the-point, tajam, analitis, dan bebas dari basa-basi.
Analisis data berikut:
- Total Invoice Lunas Bulan Ini: {$formattedMonthly}
- Total Tagihan Menunggak (Overdue): {$formattedOverdue}
- Rasio Kolektibilitas Invoice (Collection Rate): {$formattedCollectionRate}
- Tren Penjualan/Invoice 3 Bulan Terakhir: {$trendText}

Anda wajib menyusun respons Anda dengan format terstruktur persis seperti ini:
[Analisis Data]
(Analisis singkat berbasis angka riil)

[Dampak Bisnis]
(Dampak langsung/penyebab masalah piutang atau arus kas J&J GROUP)

[Rekomendasi Aksi]
(Rekomendasi tindakan nyata yang spesifik dan langsung dapat dieksekusi)

Aturan:
- Gunakan bahasa Indonesia profesional yang tegas, ringkas, dan langsung pada sasaran.
- JANGAN gunakan format markdown lain (seperti tanda bintang tebal/miring, list bulat, dll). Cukup teks bersih berparagraf yang dipisahkan oleh tag baris di atas.";
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
                if ($locale === 'en') {
                    if ($overdueRevenue > 0) {
                        return "[Analisis Data]\nTotal overdue invoices stand at {$formattedOverdue} this month, with current month paid invoices at {$formattedMonthly}. The collection rate is currently at {$formattedCollectionRate}.\n\n[Dampak Bisnis]\nThese outstanding receivables delay J&J GROUP's operational cash cycle, locking up vital working capital.\n\n[Rekomendasi Aksi]\nPrioritize immediate collections on major accounts and pause further project milestones for overdue accounts." . $errMsg;
                    } else {
                        return "[Analisis Data]\nAll invoices are settled, achieving a {$formattedCollectionRate} collection efficiency this month.\n\n[Dampak Bisnis]\nStrong collection rates maximize cash buffer availability, enabling smooth short-term working capital.\n\n[Rekomendasi Aksi]\nContinue active monitoring and allocate a portion of realized cash to operational reserves." . $errMsg;
                    }
                } else {
                    if ($overdueRevenue > 0) {
                        return "[Analisis Data]\nTotal tagihan menunggak (piutang overdue) saat ini mencapai {$formattedOverdue}, sedangkan invoice lunas bulan ini sebesar {$formattedMonthly}. Rasio kolektibilitas berada di angka {$formattedCollectionRate}.\n\n[Dampak Bisnis]\nPiutang yang menunggak menghambat perputaran kas operasional J&J GROUP dan membebani likuiditas jangka pendek.\n\n[Rekomendasi Aksi]\nSegera kirimkan surat pengingat resmi ke klien bersangkutan dan batasi pengerjaan milestone proyek berjalan." . $errMsg;
                    } else {
                        return "[Analisis Data]\nSeluruh tagihan bulan ini telah lunas, mencapai efisiensi rasio kolektibilitas {$formattedCollectionRate}.\n\n[Dampak Bisnis]\nKetepatan pelunasan tagihan mengamankan ketersediaan kas untuk pembiayaan operasional harian.\n\n[Rekomendasi Aksi]\nTetap pertahankan penagihan berkala dan alokasikan sebagian keuntungan bersih ke cadangan kas." . $errMsg;
                    }
                }
            }
        };

        if ($force) {
            Cache::forget('ai_financial_insights_' . $locale);
            $insight = $fetchCallback();
            Cache::put('ai_financial_insights_' . $locale, $insight, 3600);
            $this->aiInsight = $insight;
        } else {
            $this->aiInsight = Cache::remember('ai_financial_insights_' . $locale, 3600, $fetchCallback);
        }
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
