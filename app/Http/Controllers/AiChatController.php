<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Carbon\Carbon;

class AiChatController extends Controller
{
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

        $context = "Kamu adalah Asisten Virtual Rooterin-Invoice. Kamu bertugas membantu Admin/Owner menganalisis data keuangan, invoice, dan klien. Jawablah pertanyaan pengguna dengan sopan, taktis, dan ringkas dalam Bahasa Indonesia.

Berikut adalah data ringkasan terkini dari sistem:
- Jumlah Klien Aktif: {$totalClients}
- Invoice Lunas: {$paidCount} buah (Total nominal: Rp " . number_format($paidTotal, 0, ',', '.') . ")
- Invoice Pending/Belum Lunas: {$pendingCount} buah (Total nominal: Rp " . number_format($pendingTotal, 0, ',', '.') . ")

Daftar Invoice Menunggak/Overdue:
{$overdueText}

Gunakan data di atas untuk menjawab pertanyaan pengguna dengan tepat. Jika pengguna menanyakan di luar konteks tagihan/klien/keuangan, arahkan mereka dengan sopan untuk fokus pada data tagihan.";

        try {
            if (empty(env('GEMINI_API_KEY')) && empty(config('gemini.api_key'))) {
                throw new \Exception("GEMINI_API_KEY tidak dikonfigurasi di file .env");
            }

            $userMessage = $request->input('message');
            $prompt = "{$context}\n\nPertanyaan Pengguna: {$userMessage}\n\nJawaban:";

            $result = Gemini::generativeModel('gemini-1.5-flash')->generateContent($prompt);
            $reply = trim($result->text());

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'is_fallback' => false
            ]);

        } catch (\Throwable $e) {
            $userMessageLower = strtolower($request->input('message'));
            $reply = "Halo! Maaf, koneksi AI asisten sedang terganggu. Namun, berikut adalah data sistem terbaru yang dapat saya temukan:\n\n";

            if (str_contains($userMessageLower, 'tunggak') || str_contains($userMessageLower, 'belum bayar') || str_contains($userMessageLower, 'overdue')) {
                $reply .= "Saat ini terdapat {$pendingCount} invoice belum lunas dengan total nilai Rp " . number_format($pendingTotal, 0, ',', '.') . ".\n\nBerikut daftar menunggak:\n{$overdueText}";
            } elseif (str_contains($userMessageLower, 'lunas') || str_contains($userMessageLower, 'bayar') || str_contains($userMessageLower, 'revenue')) {
                $reply .= "Total invoice lunas: {$paidCount} invoice dengan nominal Rp " . number_format($paidTotal, 0, ',', '.') . ".";
            } elseif (str_contains($userMessageLower, 'klien') || str_contains($userMessageLower, 'client')) {
                $reply .= "Jumlah klien aktif saat ini adalah {$totalClients} klien.";
            } else {
                $reply .= "Sistem mencatat:\n- {$totalClients} klien aktif\n- {$paidCount} invoice lunas (Rp " . number_format($paidTotal, 0, ',', '.') . ")\n- {$pendingCount} invoice belum lunas (Rp " . number_format($pendingTotal, 0, ',', '.') . ").\n\nSilakan atur GEMINI_API_KEY di file .env Anda untuk mengaktifkan kecerdasan penuh asisten ini.";
            }

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'is_fallback' => true
            ]);
        }
    }
}
