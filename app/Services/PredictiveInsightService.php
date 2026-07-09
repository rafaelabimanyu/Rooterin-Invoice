<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class PredictiveInsightService
{
    protected $reportingService;

    public function __construct(BusinessUnitReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    /**
     * Analyze financial state and generate automated strategic insights.
     *
     * @param array $filters
     * @return array
     */
    public function generateInsights(array $filters = [])
    {
        $insights = [];
        $stats = $this->reportingService->getSummaryStats($filters);

        // 1. Check Collection Rate
        if ($stats['total_invoices_count'] > 0) {
            $rate = $stats['collection_rate'];
            if ($rate < 75) {
                $insights[] = [
                    'type' => 'danger',
                    'title' => 'Tingkat Koleksi Kritis (' . number_format($rate, 1) . '%)',
                    'message' => 'Rasio tagihan yang berhasil dilunasi berada di bawah batas minimum 75%.',
                    'recommendation' => 'Disarankan untuk mengevaluasi kembali tenggat waktu jatuh tempo (Term of Payment) dan mewajibkan uang muka (DP) minimal 30% untuk proyek baru.',
                    'icon' => 'trending-down'
                ];
            } elseif ($rate < 85) {
                $insights[] = [
                    'type' => 'warning',
                    'title' => 'Tingkat Koleksi Di Bawah Target (' . number_format($rate, 1) . '%)',
                    'message' => 'Rasio tagihan terbayar belum mencapai target optimal 85%.',
                    'recommendation' => 'Disarankan mengaktifkan notifikasi pengingat otomatis H-3 sebelum tanggal jatuh tempo kepada para klien.',
                    'icon' => 'trending-down'
                ];
            } else {
                $insights[] = [
                    'type' => 'success',
                    'title' => 'Koleksi Sangat Sehat (' . number_format($rate, 1) . '%)',
                    'message' => 'Rasio pelunasan tagihan berjalan dengan sangat baik dan efisien.',
                    'recommendation' => 'Pertahankan skema penagihan saat ini. Lakukan apresiasi atau program loyalitas bagi klien yang selalu membayar tepat waktu.',
                    'icon' => 'trending-up'
                ];
            }
        }

        // 2. Check Overdue Receivables
        if ($stats['overdue_outstanding'] > 0) {
            $overduePct = $stats['total_outstanding'] > 0 ? ($stats['overdue_outstanding'] / $stats['total_outstanding']) * 100 : 0;
            if ($overduePct > 20) {
                $insights[] = [
                    'type' => 'danger',
                    'title' => 'Piutang Macet Tinggi (' . number_format($overduePct, 1) . '%)',
                    'message' => 'Jumlah piutang jatuh tempo (Rp ' . number_format($stats['overdue_outstanding'], 0, ',', '.') . ') melebihi 20% dari total piutang aktif.',
                    'recommendation' => 'Segera hubungi perwakilan hukum atau kirimkan Surat Peringatan (SP) pertama ke klien yang menunggak lebih dari 30 hari.',
                    'icon' => 'alert-circle'
                ];
            }
        }

        // 3. Check Client Concentration Risk
        $highestUnpaid = Invoice::where('status', '!=', 'paid')
            ->select('client_id', DB::raw('SUM(total) as total_unpaid'))
            ->groupBy('client_id')
            ->orderByDesc('total_unpaid')
            ->first();

        if ($highestUnpaid && $stats['total_outstanding'] > 0) {
            $ratio = $highestUnpaid->total_unpaid / $stats['total_outstanding'];
            if ($ratio > 0.40) {
                $client = Client::find($highestUnpaid->client_id);
                if ($client) {
                    $insights[] = [
                        'type' => 'warning',
                        'title' => 'Risiko Kredit Terkonsentrasi (' . number_format($ratio * 100, 1) . '%)',
                        'message' => "Klien '{$client->nama_client}' bertanggung jawab atas Rp " . number_format($highestUnpaid->total_unpaid, 0, ',', '.') . " dari total piutang saat ini.",
                        'recommendation' => "Lebih dari 40% piutang Anda tertahan pada satu klien. Batasi penambahan order baru untuk klien ini sampai tunggakan sebagian besar dilunasi.",
                        'icon' => 'alert-triangle'
                    ];
                }
            }
        }

        // Default positive insight if no risks found
        if (empty($insights)) {
            $insights[] = [
                'type' => 'success',
                'title' => 'Keuangan Berjalan Lancar',
                'message' => 'Sistem tidak mendeteksi adanya anomali piutang macet atau konsentrasi risiko kredit.',
                'recommendation' => 'Seluruh metrik operasional unit bisnis berada dalam batas aman dan stabil.',
                'icon' => 'check-circle'
            ];
        }

        return $insights;
    }
}
