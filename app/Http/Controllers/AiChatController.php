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
            $apiKey = env('GEMINI_API_KEY') ?: config('gemini.api_key');
            if (empty($apiKey)) {
                throw new \Exception("GEMINI_API_KEY tidak dikonfigurasi di file .env");
            }

            $userMessage = $request->input('message');
            $prompt = "{$context}\n\nPertanyaan Pengguna: {$userMessage}\n\nJawaban:";

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
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

            $reply = trim($reply);

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'is_fallback' => false
            ]);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiChatController Error (switching to local fallback): " . $e->getMessage(), ['exception' => $e]);
            
            // Fallback Engine
            $userMsgLower = strtolower($userMessage ?? '');
            $reply = "";
            $navigateTag = "";

            if (str_contains($userMsgLower, 'klien') || str_contains($userMsgLower, 'client')) {
                $reply = "Halo! Saat ini sistem mencatat Anda memiliki **{$totalClients} klien aktif**. Anda dapat melihat dan mengelola detail data klien secara lengkap di halaman Klien.";
                $navigateTag = " [NAVIGATE: clients.index]";
            } elseif (str_contains($userMsgLower, 'lunas') || str_contains($userMsgLower, 'paid')) {
                $reply = "Tentu! Total nominal tagihan yang telah lunas (paid) adalah **Rp " . number_format($paidTotal, 0, ',', '.') . "** dari total **{$paidCount} invoice**.";
                $navigateTag = " [NAVIGATE: invoices.index]";
            } elseif (str_contains($userMsgLower, 'menunggak') || str_contains($userMsgLower, 'overdue') || str_contains($userMsgLower, 'jatuh tempo')) {
                $replyList = [];
                foreach ($overdueInvoices as $inv) {
                    $replyList[] = "* **Invoice #{$inv->invoice_number}** oleh {$inv->client->nama_client} - Rp " . number_format($inv->total, 0, ',', '.') . " (Jatuh tempo: " . $inv->due_date->format('d M Y') . ")";
                }
                $reply = "Berikut adalah daftar invoice yang saat ini berstatus menunggak (overdue):\n\n" . (count($replyList) > 0 ? implode("\n", $replyList) : "Tidak ada invoice menunggak saat ini.");
                $navigateTag = " [NAVIGATE: invoices.index]";
            } elseif (str_contains($userMsgLower, 'buat invoice') || str_contains($userMsgLower, 'tambah invoice') || str_contains($userMsgLower, 'create invoice')) {
                $reply = "Untuk membuat invoice baru, Anda dapat langsung mengisi form pembuatan invoice pada halaman yang telah disediakan.";
                $navigateTag = " [NAVIGATE: invoices.create]";
            } elseif (str_contains($userMsgLower, 'tambah klien') || str_contains($userMsgLower, 'buat klien') || str_contains($userMsgLower, 'create client')) {
                $reply = "Untuk menambahkan klien baru ke dalam sistem, silakan isi form tambah klien pada halaman manajemen klien.";
                $navigateTag = " [NAVIGATE: clients.create]";
            } elseif (str_contains($userMsgLower, 'kuitansi') || str_contains($userMsgLower, 'receipt') || str_contains($userMsgLower, 'tanda terima')) {
                $reply = "Anda dapat melihat daftar kuitansi atau membuat kuitansi baru melalui menu kuitansi.";
                if (str_contains($userMsgLower, 'buat') || str_contains($userMsgLower, 'tambah')) {
                    $navigateTag = " [NAVIGATE: receipts.create]";
                } else {
                    $navigateTag = " [NAVIGATE: receipts.index]";
                }
            } elseif (str_contains($userMsgLower, 'pengaturan') || str_contains($userMsgLower, 'setting')) {
                $reply = "Anda dapat mengatur data profil perusahaan, integrasi pihak ketiga, dan pengaturan umum sistem melalui halaman Pengaturan.";
                $navigateTag = " [NAVIGATE: settings.index]";
            } elseif (str_contains($userMsgLower, 'profil') || str_contains($userMsgLower, 'akun') || str_contains($userMsgLower, 'profile')) {
                $reply = "Untuk memperbarui detail akun Anda seperti nama, email, dan password, silakan buka halaman Profil Pengguna.";
                $navigateTag = " [NAVIGATE: profile.edit]";
            } elseif (str_contains($userMsgLower, 'dashboard')) {
                $reply = "Di halaman dashboard, Anda dapat memantau grafik penjualan bulanan, total tagihan outstanding, ringkasan aktivitas, serta analisis cashflow secara visual.";
                $navigateTag = " [NAVIGATE: dashboard]";
            } else {
                // Default sapaan asisten ramah
                $reply = "Halo! Saya adalah Asisten Virtual Rooterin-Invoice. Saat ini saya beroperasi dalam mode asisten lokal.\n\nBerikut ringkasan data bisnis Anda:\n* **Jumlah Klien Aktif:** {$totalClients}\n* **Invoice Lunas:** {$paidCount} buah (Total: Rp " . number_format($paidTotal, 0, ',', '.') . ")\n* **Invoice Belum Lunas:** {$pendingCount} buah (Total: Rp " . number_format($pendingTotal, 0, ',', '.') . ")\n\nApakah ada hal lain terkait invoice, klien, atau navigasi sistem yang ingin Anda tanyakan?";
            }

            $reply .= $navigateTag;

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'is_fallback' => true
            ]);
        }
    }
}
