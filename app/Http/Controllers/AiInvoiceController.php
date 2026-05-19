<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class AiInvoiceController extends Controller
{
    public function generateEmailDraft(Invoice $invoice, Request $request)
    {
        $request->validate([
            'tone' => 'required|string|in:sopan,tegas,urgent',
        ]);

        $tone = $request->input('tone');
        $clientName = $invoice->client->nama_client;
        $companyName = $invoice->client->nama_perusahaan ?? '-';
        $totalAmount = 'Rp ' . number_format($invoice->total, 0, ',', '.');
        $dueDate = $invoice->due_date ? $invoice->due_date->format('d M Y') : '-';
        $invoiceNumber = $invoice->invoice_number;
        $status = $invoice->status;

        // Formulate prompt context
        $toneDescription = '';
        if ($tone === 'sopan') {
            $toneDescription = 'sopan, ramah, profesional, dan sebagai pengingat bersahabat (friendly reminder)';
        } elseif ($tone === 'tegas') {
            $toneDescription = 'tegas, formal, lugas, dan meminta pembayaran segera dilakukan karena mendekati atau sudah melewati jatuh tempo';
        } elseif ($tone === 'urgent') {
            $toneDescription = 'sangat mendesak (urgent), tegas, dan memberikan peringatan keras bahwa batas waktu pembayaran telah lewat dan harus segera diselesaikan hari ini untuk menghindari denda atau penangguhan layanan';
        }

        $prompt = "Kamu adalah sistem AI Copywriter profesional untuk aplikasi Rooterin-Invoice.
Tugas kamu adalah membuat draf email penagihan pembayaran invoice dalam Bahasa Indonesia.

Informasi Invoice:
- Nomor Invoice: {$invoiceNumber}
- Nama Penerima / Klien: {$clientName}
- Nama Perusahaan Klien: {$companyName}
- Total Nominal Penagihan: {$totalAmount}
- Tanggal Jatuh Tempo: {$dueDate}
- Status Pembayaran Invoice Saat Ini: {$status}

Gaya penulisan dan nada (tone): {$toneDescription}.

Format output harus berupa JSON murni dengan struktur berikut:
{
  \"subject\": \"Subjek email yang menarik dan sesuai dengan nada penagihan\",
  \"body\": \"Isi email lengkap dengan salam pembuka, rincian tagihan, informasi instruksi pembayaran (kamu bisa sebutkan detail rekening ada di lampiran invoice), serta salam penutup yang profesional. Gunakan baris baru (\\n) untuk pemisah paragraf.\"
}

PENTING: Jangan sertakan blok kode markdown seperti ```json atau pembungkus teks apa pun. Kembalikan langsung string JSON murni agar bisa diparse langsung oleh `json_decode`. Jika kamu tidak bisa melakukannya, kembalikan saja string JSON tanpa formatting.";

        try {
            // Check if GEMINI_API_KEY is configured
            if (empty(env('GEMINI_API_KEY')) && empty(config('gemini.api_key'))) {
                throw new \Exception("GEMINI_API_KEY tidak dikonfigurasi di file .env");
            }

            $result = Gemini::generativeModel(model: 'gemini-1.5-flash')->generateContent($prompt);
            $responseText = trim($result->text());

            // Strip potential markdown wrappers
            if (preg_match('/^```json\s*(.*?)\s*```$/is', $responseText, $matches)) {
                $responseText = $matches[1];
            } elseif (preg_match('/^```\s*(.*?)\s*```$/is', $responseText, $matches)) {
                $responseText = $matches[1];
            }

            $emailData = json_decode(trim($responseText), true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($emailData['subject']) || !isset($emailData['body'])) {
                // Parse fallback if JSON decoding fails
                $emailData = [
                    'subject' => "Pengingat Pembayaran Tagihan: Invoice #{$invoiceNumber}",
                    'body' => "Halo {$clientName},\n\nSemoga Anda dalam keadaan baik.\n\nKami ingin mengingatkan mengenai pembayaran Invoice #{$invoiceNumber} sebesar {$totalAmount} yang jatuh tempo pada {$dueDate}.\n\nMohon lakukan pembayaran sesuai instruksi. Terima kasih atas kerja sama Anda.\n\nSalam hangat,\nRooterin Technical Services",
                    'is_fallback' => true,
                    'raw_response' => $responseText
                ];
            }

            return response()->json([
                'success' => true,
                'subject' => $emailData['subject'],
                'body' => $emailData['body'],
                'is_fallback' => $emailData['is_fallback'] ?? false
            ]);

        } catch (\Throwable $e) {
            // Provide a graceful fallback if Gemini API fails
            $subjectFallback = "Pengingat Pembayaran Tagihan: Invoice #{$invoiceNumber}";
            if ($tone === 'urgent') {
                $subjectFallback = "[URGENT] Segera Selesaikan Pembayaran: Invoice #{$invoiceNumber}";
            } elseif ($tone === 'tegas') {
                $subjectFallback = "Pemberitahuan Penagihan: Invoice #{$invoiceNumber}";
            }

            $bodyFallback = "Halo {$clientName},\n\nSemoga Anda dalam keadaan baik.\n\nKami mengirimkan email ini sebagai draf pengingat pembayaran untuk Invoice #{$invoiceNumber} sebesar {$totalAmount} yang jatuh tempo pada {$dueDate}.\n\nHarap segera menyelesaikan pembayaran sesuai dengan instruksi yang tertera pada dokumen invoice Anda.\n\nJika Anda sudah melakukan pembayaran, mohon abaikan pesan ini.\n\nTerima kasih atas kerja samanya.\n\nSalam hangat,\nRooterin Technical Services";

            return response()->json([
                'success' => true,
                'subject' => $subjectFallback,
                'body' => $bodyFallback,
                'is_fallback' => true,
                'warning' => 'Menggunakan draf lokal (Gemini API bermasalah atau API Key belum diset).'
            ]);
        }
    }
}
