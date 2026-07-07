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
        $locale = app()->getLocale();
        $toneDescription = '';
        if ($locale === 'en') {
            if ($tone === 'sopan') {
                $toneDescription = 'polite, friendly, professional, and acts as a friendly reminder';
            } elseif ($tone === 'tegas') {
                $toneDescription = 'assertive, formal, straightforward, and requests immediate payment as the due date is near or has passed';
            } elseif ($tone === 'urgent') {
                $toneDescription = 'highly urgent, firm, warning that the deadline has passed and must be settled today to avoid penalties or service suspension';
            }
        } else {
            if ($tone === 'sopan') {
                $toneDescription = 'sopan, ramah, profesional, dan sebagai pengingat bersahabat (friendly reminder)';
            } elseif ($tone === 'tegas') {
                $toneDescription = 'tegas, formal, lugas, dan meminta pembayaran segera dilakukan karena mendekati atau sudah melewati jatuh tempo';
            } elseif ($tone === 'urgent') {
                $toneDescription = 'sangat mendesak (urgent), tegas, dan memberikan peringatan keras bahwa batas waktu pembayaran telah lewat dan harus segera diselesaikan hari ini untuk menghindari denda atau penangguhan layanan';
            }
        }

        if ($locale === 'en') {
            $prompt = "You are a professional AI Copywriter for the J&J GROUP Invoice application.
Your task is to generate an invoice payment reminder email draft.

Invoice Information:
- Invoice Number: {$invoiceNumber}
- Client Name: {$clientName}
- Client Company Name: {$companyName}
- Total Amount: {$totalAmount}
- Due Date: {$dueDate}
- Current Invoice Payment Status: {$status}

Tone of writing: {$toneDescription}.

Strictly match the user's current application language interface. Since the active language is 'en', you MUST construct your entire email subject and body in Professional English. If the active language is 'id', you MUST respond in Professional Indonesian. Never mix the languages.

The output format must be pure JSON with the following structure:
{
  \"subject\": \"Email subject that matches the tone and context\",
  \"body\": \"Full email body including greetings, invoice details, payment instructions (mention that bank details are attached to the invoice), and a professional sign-off. Use newlines (\\n) for paragraph spacing.\"
}

IMPORTANT: Do not include markdown code blocks like ```json or any other wrappers. Return pure JSON string so it can be directly parsed. If you cannot do this, return the JSON string without formatting.";
        } else {
            $prompt = "Kamu adalah sistem AI Copywriter profesional untuk aplikasi J&J GROUP Invoice.
Tugas kamu adalah membuat draf email penagihan pembayaran invoice dalam Bahasa Indonesia.

Informasi Invoice:
- Nomor Invoice: {$invoiceNumber}
- Nama Penerima / Klien: {$clientName}
- Nama Perusahaan Klien: {$companyName}
- Total Nominal Penagihan: {$totalAmount}
- Tanggal Jatuh Tempo: {$dueDate}
- Status Pembayaran Invoice Saat Ini: {$status}

Gaya penulisan dan nada (tone): {$toneDescription}.

Strictly match the user's current application language interface. Since the active language is 'en', you MUST construct your entire email subject and body in Professional English. If the active language is 'id', you MUST respond in Professional Indonesian. Never mix the languages.

Format output harus berupa JSON murni dengan struktur berikut:
{
  \"subject\": \"Subjek email yang menarik dan sesuai dengan nada penagihan\",
  \"body\": \"Isi email lengkap dengan salam pembuka, rincian tagihan, informasi instruksi pembayaran (kamu bisa sebutkan detail rekening ada di lampiran invoice), serta salam penutup yang profesional. Gunakan baris baru (\\n) untuk pemisah paragraf.\"
}

PENTING: Jangan sertakan blok kode markdown seperti ```json atau pembungkus teks apa pun. Kembalikan langsung string JSON murni agar bisa diparse langsung oleh `json_decode`. Jika kamu tidak bisa melakukannya, kembalikan saja string JSON tanpa formatting.";
        }

        try {
            $apiKey = env('GEMINI_API_KEY') ?: config('gemini.api_key');
            if (empty($apiKey)) {
                throw new \Exception("GEMINI_API_KEY tidak dikonfigurasi di file .env");
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
            $responseText = $resData['candidates'][0]['content']['parts'][0]['text'] ?? null;
            
            if (empty($responseText)) {
                throw new \Exception("Response format invalid");
            }
            
            $responseText = trim($responseText);

            // Strip potential markdown wrappers
            if (preg_match('/^```json\s*(.*?)\s*```$/is', $responseText, $matches)) {
                $responseText = $matches[1];
            } elseif (preg_match('/^```\s*(.*?)\s*```$/is', $responseText, $matches)) {
                $responseText = $matches[1];
            }

            $emailData = json_decode(trim($responseText), true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($emailData['subject']) || !isset($emailData['body'])) {
                // Parse fallback if JSON decoding fails
                if ($locale === 'en') {
                    $emailData = [
                        'subject' => "Payment Reminder: Invoice #{$invoiceNumber}",
                        'body' => "Dear {$clientName},\n\nHope this email finds you well.\n\nWe would like to remind you regarding the payment for Invoice #{$invoiceNumber} amounting to {$totalAmount} which is due on {$dueDate}.\n\nPlease arrange for the payment at your earliest convenience. Thank you for your cooperation.\n\nBest regards,\nJ&J GROUP Technical Services",
                        'is_fallback' => true,
                        'raw_response' => $responseText
                    ];
                } else {
                    $emailData = [
                        'subject' => "Pengingat Pembayaran Tagihan: Invoice #{$invoiceNumber}",
                        'body' => "Halo {$clientName},\n\nSemoga Anda dalam keadaan baik.\n\nKami ingin mengingatkan mengenai pembayaran Invoice #{$invoiceNumber} sebesar {$totalAmount} yang jatuh tempo pada {$dueDate}.\n\nMohon lakukan pembayaran sesuai instruksi. Terima kasih atas kerja sama Anda.\n\nSalam hangat,\nJ&J GROUP Technical Services",
                        'is_fallback' => true,
                        'raw_response' => $responseText
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'subject' => $emailData['subject'],
                'body' => $emailData['body'],
                'is_fallback' => $emailData['is_fallback'] ?? false
            ]);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiInvoiceController Error: " . $e->getMessage(), ['exception' => $e]);
            // Provide a graceful fallback if Gemini API fails
            if ($locale === 'en') {
                $subjectFallback = "Payment Reminder: Invoice #{$invoiceNumber}";
                if ($tone === 'urgent') {
                    $subjectFallback = "[URGENT] Payment Due Immediately: Invoice #{$invoiceNumber}";
                } elseif ($tone === 'tegas') {
                    $subjectFallback = "Billing Notice: Invoice #{$invoiceNumber}";
                }

                $bodyFallback = "Dear {$clientName},\n\nHope this email finds you well.\n\nThis is a payment reminder for Invoice #{$invoiceNumber} amounting to {$totalAmount} which is due on {$dueDate}.\n\nPlease settle the payment according to the instructions on your invoice document.\n\nIf you have already made the payment, please disregard this email.\n\nThank you for your cooperation.\n\nBest regards,\nJ&J GROUP Technical Services";
            } else {
                $subjectFallback = "Pengingat Pembayaran Tagihan: Invoice #{$invoiceNumber}";
                if ($tone === 'urgent') {
                    $subjectFallback = "[URGENT] Segera Selesaikan Pembayaran: Invoice #{$invoiceNumber}";
                } elseif ($tone === 'tegas') {
                    $subjectFallback = "Pemberitahuan Penagihan: Invoice #{$invoiceNumber}";
                }

                $bodyFallback = "Halo {$clientName},\n\nSemoga Anda dalam keadaan baik.\n\nKami mengirimkan email ini sebagai draf pengingat pembayaran untuk Invoice #{$invoiceNumber} sebesar {$totalAmount} yang jatuh tempo pada {$dueDate}.\n\nHarap segera menyelesaikan pembayaran sesuai dengan instruksi yang tertera pada dokumen invoice Anda.\n\nJika Anda sudah melakukan pembayaran, mohon abaikan pesan ini.\n\nTerima kasih atas kerja samanya.\n\nSalam hangat,\nJ&J GROUP Technical Services";
            }

            return response()->json([
                'success' => true,
                'subject' => $subjectFallback,
                'body' => $bodyFallback,
                'is_fallback' => true,
                'warning' => $locale === 'en' ? 'Using local fallback draft.' : 'Menggunakan draf lokal.'
            ]);
        }
    }
}
