<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Carbon\Carbon;

class AiChatController extends Controller
{
    public function index()
    {
        return view('ai-assistant.index');
    }

    public function handleChat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $totalClients = \App\Models\Client::where('status', 'aktif')->count();
        $paidCount = \App\Models\Invoice::where('status', 'paid')->count();
        $paidTotal = \App\Models\Invoice::where('status', 'paid')->sum('total');
        $pendingCount = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])->count();
        $pendingTotal = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])->sum('total');

        // Overdue invoices
        $overdueInvoices = \App\Models\Invoice::with('client')
            ->whereIn('status', ['sent', 'pending', 'dp'])
            ->where('due_date', '<', Carbon::now())
            ->get();

        $overdueList = [];
        foreach ($overdueInvoices as $inv) {
            $overdueList[] = "- Invoice #{$inv->invoice_number} oleh {$inv->client->nama_client} ({$inv->client->nama_perusahaan}): sebesar Rp " . number_format($inv->total, 0, ',', '.') . " (Jatuh tempo: " . $inv->due_date->format('d M Y') . ")";
        }
        $overdueText = count($overdueList) > 0 ? implode("\n", $overdueList) : "Tidak ada invoice menunggak.";

        $context = "Kamu adalah Asisten Virtual Rooterin-Invoice. Kamu bertugas membantu Admin/Owner/Staff menganalisis data keuangan, invoice, dan klien. Jawablah pertanyaan pengguna dengan sopan, taktis, dan ringkas dalam Bahasa Indonesia.

Berikut adalah data ringkasan terkini dari sistem:
- Jumlah Klien Aktif: {$totalClients}
- Invoice Lunas: {$paidCount} buah (Total nominal: Rp " . number_format($paidTotal, 0, ',', '.') . ")
- Invoice Pending/Belum Lunas: {$pendingCount} buah (Total nominal: Rp " . number_format($pendingTotal, 0, ',', '.') . ")

Daftar Invoice Menunggak/Overdue:
{$overdueText}

Aturan Khusus Navigasi:
Jika pengguna menanyakan letak, lokasi, atau cara menuju ke suatu halaman (seperti halaman invoice, klien, dashboard, atau pengaturan), sertakan tag format khusus di akhir jawaban Anda seperti ini: `[NAVIGATE: nama-route]`. 
Gunakan hanya route berikut jika cocok:
- `dashboard` -> Dashboard utama
- `invoices.index` -> Daftar Invoice
- `invoices.create` -> Buat Invoice Baru
- `clients.index` -> Daftar Klien
- `clients.create` -> Tambah Klien Baru
- `receipts.index` -> Daftar Kuitansi / Tanda Terima
- `receipts.create` -> Buat Kuitansi / Tanda Terima Baru
- `settings.index` -> Pengaturan Aplikasi
- `profile.edit` -> Profil Pengguna
Contoh: `[NAVIGATE: invoices.index]` atau `[NAVIGATE: clients.index]`.

Gunakan data di atas untuk menjawab pertanyaan pengguna dengan tepat. Jika pengguna menanyakan di luar konteks tagihan/klien/keuangan, arahkan mereka dengan sopan untuk fokus pada data tagihan.";

        try {
            if (empty(env('GEMINI_API_KEY')) && empty(config('gemini.api_key'))) {
                throw new \Exception("GEMINI_API_KEY tidak dikonfigurasi di file .env");
            }

            $userMessage = $request->input('message');
            $prompt = "{$context}\n\nPertanyaan Pengguna: {$userMessage}\n\nJawaban:";

            $result = Gemini::generativeModel('gemini-pro')->generateContent($prompt);
            $reply = trim($result->text());

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'is_fallback' => false
            ]);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiChatController Error: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'error' => 'Koneksi ke Gemini API terganggu. Detail: ' . $e->getMessage()
            ], 500);
        }
    }
}
