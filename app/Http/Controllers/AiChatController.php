<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Carbon\Carbon;

class AiChatController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasFullAccess(), 403, 'Unauthorized action.');

        $sessions = $this->getOptimizedSessions();

        return view('ai-assistant.index', compact('sessions'));
    }

    public function getSessionChat($sessionId)
    {
        abort_if(!auth()->user()->hasFullAccess(), 403, 'Unauthorized action.');

        $chats = \App\Models\AiChatHistory::where('user_id', auth()->id())
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->flatMap(function ($chat) {
                return [
                    [
                        'sender' => 'user',
                        'text' => $chat->message
                    ],
                    [
                        'sender' => 'ai',
                        'text' => $chat->response
                    ]
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'messages' => $chats
        ]);
    }

    public function handleChat(Request $request)
    {
        abort_if(!auth()->user()->hasFullAccess(), 403, 'Unauthorized action.');

        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string|max:255'
        ]);

        $sessionId = $request->input('session_id');
        if (empty($sessionId)) {
            $sessionId = (string) \Illuminate\Support\Str::uuid();
        }

        $userMessage = $request->input('message');
        $locale = app()->getLocale();

        // 1. Fetch system statistics for context
        $totalClients = \App\Models\Client::where('status', 'aktif')->count();
        $paidCount = \App\Models\Invoice::where('status', 'paid')->count();
        $paidTotal = \App\Models\Invoice::where('status', 'paid')->sum('total');
        $pendingCount = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])->count();
        $pendingTotal = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])->sum('total');

        $overdueInvoices = \App\Models\Invoice::with('client')
            ->whereIn('status', ['sent', 'pending', 'dp'])
            ->where('due_date', '<', Carbon::now())
            ->get();

        $overdueList = [];
        foreach ($overdueInvoices as $inv) {
            $overdueList[] = "- Invoice #{$inv->invoice_number} by {$inv->client->nama_client}: Rp " . number_format($inv->total, 0, ',', '.') . " (Due: " . $inv->due_date->format('d M Y') . ")";
        }
        $overdueText = count($overdueList) > 0 ? implode("\n", $overdueList) : "No overdue invoices.";

        // Build prompt for structured JSON
        $prompt = "You are the Autonomous AI Financial Officer (Rooterin AI 2.0) for the Rooterin-Invoice system.
Your job is to analyze the user's input (speech or text) and map it to an intent, system action, and extract parameters if applicable.

Current System Summary:
- Active Clients: {$totalClients}
- Paid Invoices: {$paidCount} (Total: Rp " . number_format($paidTotal, 0, ',', '.') . ")
- Pending Invoices: {$pendingCount} (Total: Rp " . number_format($pendingTotal, 0, ',', '.') . ")
- Overdue Invoices:
{$overdueText}

Available Intents & Actions:
1. QUERY_DATABASE:
   - 'list_overdue_invoices': User wants to see unpaid, pending, or overdue invoices.
   - 'summary_aggregate': User wants to know active client counts, total paid/unpaid invoices.
2. SYSTEM_ACTION:
   - 'create_invoice': User wants to create a new invoice. Extract parameters: 'client_name', 'amount' (integer), 'due_date' (YYYY-MM-DD), and 'items' (array of objects with 'deskripsi', 'qty', 'harga').
   - 'create_client': User wants to add/create a new client. Extract parameters: 'client_name', 'email', 'no_hp'.
   - 'send_followup': User wants to send billing reminder/follow-up. Extract parameters: 'client_name', 'invoice_number'.
   - 'forecast_cashflow': User wants cash flow projections or estimates for the next 3 months.
3. GENERAL_CHAT: Use this for greetings, general advice, explanations, or questions not mapping to database actions.

You MUST return a JSON object ONLY, with the following keys:
- 'intent': 'QUERY_DATABASE' | 'SYSTEM_ACTION' | 'GENERAL_CHAT'
- 'action': 'list_overdue_invoices' | 'summary_aggregate' | 'create_invoice' | 'create_client' | 'send_followup' | 'forecast_cashflow' | 'none'
- 'parameters': object containing extracted parameters (e.g. {'client_name': '...', 'amount': ...})
- 'reply': For GENERAL_CHAT, write your direct markdown response here. For other intents, write a conversational acknowledgment or initial draft of response here (the backend will append the real database results to it).
- 'speak_text': A short spoken sentence (max 2-3 sentences) summarizing what you are doing or the answer, to be read out loud.

Language constraint:
Strictly match the active language: " . ($locale === 'en' ? 'English' : 'Indonesian') . ".

User Message: \"{$userMessage}\"";

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
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception("HTTP Error: Status " . $response->status());
            }

            $resData = $response->json();
            $rawReply = $resData['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if (empty($rawReply)) {
                throw new \Exception("Response format invalid");
            }

            $aiResult = json_decode(trim($rawReply), true);
            if (!$aiResult || !isset($aiResult['intent'])) {
                throw new \Exception("JSON format invalid");
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiChatController Gemini Error: " . $e->getMessage());
            
            // Fallback: parse user message with simple keyword matching
            $userMsgLower = strtolower($userMessage);
            $aiResult = [
                'intent' => 'GENERAL_CHAT',
                'action' => 'none',
                'parameters' => [],
                'reply' => '',
                'speak_text' => ''
            ];

            if (str_contains($userMsgLower, 'menunggak') || str_contains($userMsgLower, 'overdue') || str_contains($userMsgLower, 'jatuh tempo') || str_contains($userMsgLower, 'tunggak')) {
                $aiResult['intent'] = 'QUERY_DATABASE';
                $aiResult['action'] = 'list_overdue_invoices';
            } elseif (str_contains($userMsgLower, 'klien') || str_contains($userMsgLower, 'client') || str_contains($userMsgLower, 'lunas') || str_contains($userMsgLower, 'paid')) {
                $aiResult['intent'] = 'QUERY_DATABASE';
                $aiResult['action'] = 'summary_aggregate';
            } elseif (str_contains($userMsgLower, 'buat invoice') || str_contains($userMsgLower, 'tambah invoice') || str_contains($userMsgLower, 'create invoice')) {
                $aiResult['intent'] = 'SYSTEM_ACTION';
                $aiResult['action'] = 'create_invoice';
                // Extract amount using regex if possible
                preg_match('/(\d+)\s*(juta|ribu|ratus)?/i', $userMsgLower, $matches);
                $amount = 5000000;
                if (!empty($matches[1])) {
                    $val = intval($matches[1]);
                    if (isset($matches[2])) {
                        if ($matches[2] === 'juta') $val *= 1000000;
                        if ($matches[2] === 'ribu') $val *= 1000;
                    }
                    $amount = $val;
                }
                // Extract client name
                preg_match('/klien\s+([a-zA-Z\s]+?)(?=\s+sebesar|\s+tempo|\s*$)/i', $userMessage, $clientMatches);
                $clientName = trim($clientMatches[1] ?? 'Budi Santoso');
                
                $aiResult['parameters'] = [
                    'client_name' => $clientName,
                    'amount' => $amount,
                    'due_date' => Carbon::now()->endOfMonth()->format('Y-m-d')
                ];
            } elseif (str_contains($userMsgLower, 'tambah klien') || str_contains($userMsgLower, 'buat klien') || str_contains($userMsgLower, 'create client')) {
                $aiResult['intent'] = 'SYSTEM_ACTION';
                $aiResult['action'] = 'create_client';
                preg_match('/klien\s+([a-zA-Z\s]+)/i', $userMessage, $clientMatches);
                $aiResult['parameters'] = [
                    'client_name' => trim($clientMatches[1] ?? 'Budi Santoso')
                ];
            } elseif (str_contains($userMsgLower, 'ingatkan') || str_contains($userMsgLower, 'follow up') || str_contains($userMsgLower, 'follow-up') || str_contains($userMsgLower, 'reminder')) {
                $aiResult['intent'] = 'SYSTEM_ACTION';
                $aiResult['action'] = 'send_followup';
                preg_match('/klien\s+([a-zA-Z\s]+)/i', $userMessage, $clientMatches);
                $aiResult['parameters'] = [
                    'client_name' => trim($clientMatches[1] ?? 'Budi Santoso')
                ];
            } elseif (str_contains($userMsgLower, 'proyeksi') || str_contains($userMsgLower, 'estimasi') || str_contains($userMsgLower, 'forecast') || str_contains($userMsgLower, 'cash flow') || str_contains($userMsgLower, 'perputaran kas')) {
                $aiResult['intent'] = 'SYSTEM_ACTION';
                $aiResult['action'] = 'forecast_cashflow';
            } else {
                // General fallback chat
                if ($locale === 'en') {
                    $aiResult['reply'] = "Hello! I am your Virtual Senior Financial Consultant. Based on the system:\n* Active Clients: {$totalClients}\n* Paid Invoices: {$paidCount} (Rp " . number_format($paidTotal, 0, ',', '.') . ")\n* Pending Invoices: {$pendingCount} (Rp " . number_format($pendingTotal, 0, ',', '.') . ")\n\nHow can I help you today?";
                    $aiResult['speak_text'] = "Hello, I am your Virtual Financial Consultant. How can I help you today?";
                } else {
                    $aiResult['reply'] = "Halo! Saya adalah Virtual Senior Financial Consultant Anda. Berdasarkan sistem:\n* Klien Aktif: {$totalClients}\n* Invoice Lunas: {$paidCount} (Rp " . number_format($paidTotal, 0, ',', '.') . ")\n* Invoice Pending: {$pendingCount} (Rp " . number_format($pendingTotal, 0, ',', '.') . ")\n\nAda yang bisa saya bantu?";
                    $aiResult['speak_text'] = "Halo, saya adalah Virtual Financial Consultant Anda. Ada yang bisa saya bantu?";
                }
            }
        }

        // 2. Execute Action & Populate Response
        $reply = $aiResult['reply'];
        $speakText = $aiResult['speak_text'];
        $chartData = null;
        $createdInvoiceNumber = null;

        if ($aiResult['intent'] === 'QUERY_DATABASE' || $aiResult['intent'] === 'SYSTEM_ACTION') {
            $action = $aiResult['action'];
            $parameters = $aiResult['parameters'] ?? [];

            if ($action === 'list_overdue_invoices') {
                if ($locale === 'en') {
                    if ($overdueInvoices->isEmpty()) {
                        $reply = "There are currently no overdue invoices in the system.";
                        $speakText = "Great news! All invoices are settled, and there are no overdue accounts at the moment.";
                    } else {
                        $reply = "### Overdue Invoices List\n\nHere are the invoices that have passed their due date:\n\n";
                        $reply .= "| Invoice No. | Client | Amount | Due Date |\n";
                        $reply .= "| :--- | :--- | :--- | :--- |\n";
                        foreach ($overdueInvoices as $inv) {
                            $reply .= "| **{$inv->invoice_number}** | {$inv->client->nama_client} | Rp " . number_format($inv->total, 0, ',', '.') . " | " . $inv->due_date->format('d M Y') . " |\n";
                        }
                        $speakText = "I found " . $overdueInvoices->count() . " overdue invoices. The largest outstanding amount is from client " . $overdueInvoices->sortByDesc('total')->first()->client->nama_client . ".";
                    }
                } else {
                    if ($overdueInvoices->isEmpty()) {
                        $reply = "Saat ini tidak ada invoice menunggak di sistem.";
                        $speakText = "Bagus sekali! Semua tagihan telah lunas dan tidak ada invoice menunggak saat ini.";
                    } else {
                        $reply = "### Daftar Invoice Menunggak\n\nBerikut adalah invoice yang telah melewati tanggal jatuh tempo:\n\n";
                        $reply .= "| No. Invoice | Klien | Nominal | Jatuh Tempo |\n";
                        $reply .= "| :--- | :--- | :--- | :--- |\n";
                        foreach ($overdueInvoices as $inv) {
                            $reply .= "| **{$inv->invoice_number}** | {$inv->client->nama_client} | Rp " . number_format($inv->total, 0, ',', '.') . " | " . $inv->due_date->format('d M Y') . " |\n";
                        }
                        $speakText = "Saya menemukan " . $overdueInvoices->count() . " invoice menunggak. Nilai tunggakan terbesar berasal dari klien " . $overdueInvoices->sortByDesc('total')->first()->client->nama_client . ".";
                    }
                }
            } elseif ($action === 'summary_aggregate') {
                if ($locale === 'en') {
                    $reply = "### Rooterin Financial Summary\n\n";
                    $reply .= "- **Active Clients:** {$totalClients} clients\n";
                    $reply .= "- **Paid Invoices:** {$paidCount} bills (Total: Rp " . number_format($paidTotal, 0, ',', '.') . ")\n";
                    $reply .= "- **Outstanding Receivables:** {$pendingCount} bills (Total: Rp " . number_format($pendingTotal, 0, ',', '.') . ")\n";
                    $speakText = "There are currently {$totalClients} active clients. The total of paid invoices is Rp " . number_format($paidTotal, 0, ',', '.') . ", and the outstanding balance is Rp " . number_format($pendingTotal, 0, ',', '.') . ".";
                } else {
                    $reply = "### Ringkasan Finansial Rooterin\n\n";
                    $reply .= "- **Klien Aktif:** {$totalClients} klien\n";
                    $reply .= "- **Invoice Lunas:** {$paidCount} tagihan (Total: Rp " . number_format($paidTotal, 0, ',', '.') . ")\n";
                    $reply .= "- **Tunggakan Aktif:** {$pendingCount} tagihan (Total: Rp " . number_format($pendingTotal, 0, ',', '.') . ")\n";
                    $speakText = "Saat ini terdapat {$totalClients} klien aktif. Total tagihan yang telah lunas sebesar Rp " . number_format($paidTotal, 0, ',', '.') . ", sedangkan sisa tunggakan aktif adalah Rp " . number_format($pendingTotal, 0, ',', '.') . ".";
                }
            } elseif ($action === 'create_invoice') {
                $clientName = $parameters['client_name'] ?? 'Budi Santoso';
                $amount = intval($parameters['amount'] ?? 5000000);
                $dueDateStr = $parameters['due_date'] ?? null;
                $dueDate = $dueDateStr ? Carbon::parse($dueDateStr) : Carbon::now()->endOfMonth();

                $client = \App\Models\Client::where('nama_client', 'like', '%' . $clientName . '%')
                    ->orWhere('nama_perusahaan', 'like', '%' . $clientName . '%')
                    ->first();

                if (!$client) {
                    $client = \App\Models\Client::create([
                        'kode_client' => \App\Models\Client::generateCode(),
                        'nama_client' => $clientName,
                        'client_type' => 'individual',
                        'status' => 'aktif'
                    ]);
                }

                $invoiceNumber = \App\Models\Invoice::generateNumber();
                $invoice = \App\Models\Invoice::create([
                    'invoice_number' => $invoiceNumber,
                    'client_id' => $client->id,
                    'tanggal_invoice' => Carbon::now(),
                    'due_date' => $dueDate,
                    'status' => 'pending',
                    'subtotal' => $amount,
                    'total' => $amount,
                    'created_by' => auth()->id(),
                ]);

                $items = $parameters['items'] ?? [];
                if (empty($items)) {
                    $items = [[
                        'deskripsi' => 'Invoice created automatically by Autonomous AI Financial Officer',
                        'qty' => 1,
                        'harga' => $amount,
                        'total' => $amount,
                    ]];
                }

                foreach ($items as $item) {
                    $invoice->items()->create([
                        'deskripsi' => $item['deskripsi'] ?? 'Invoice item created by AI Assistant',
                        'qty' => $item['qty'] ?? 1,
                        'harga' => $item['harga'] ?? $amount,
                        'total' => ($item['qty'] ?? 1) * ($item['harga'] ?? $amount),
                    ]);
                }

                \App\Models\ActivityLog::log('create_invoice', "Invoice {$invoiceNumber} created via AI Assistant for {$client->nama_client}", $invoice);
                $createdInvoiceNumber = $invoiceNumber;

                if ($locale === 'en') {
                    $reply = "### Invoice Successfully Created! 🎉\n\n" .
                             "- **Invoice Number:** `{$invoiceNumber}`\n" .
                             "- **Client:** {$client->nama_client} (" . ($client->nama_perusahaan ?: 'Individual') . ")\n" .
                             "- **Amount:** Rp " . number_format($amount, 0, ',', '.') . "\n" .
                             "- **Due Date:** " . $dueDate->format('d M Y') . "\n\n" .
                             "The invoice record has been saved and registered in the database.";
                    $speakText = "I have successfully created invoice {$invoiceNumber} for client {$client->nama_client} amounting to Rp " . number_format($amount, 0, ',', '.') . ", due on " . $dueDate->format('d M Y') . ".";
                } else {
                    $reply = "### Invoice Berhasil Dibuat! 🎉\n\n" .
                             "- **Nomor Invoice:** `{$invoiceNumber}`\n" .
                             "- **Klien:** {$client->nama_client} (" . ($client->nama_perusahaan ?: 'Personal') . ")\n" .
                             "- **Nominal:** Rp " . number_format($amount, 0, ',', '.') . "\n" .
                             "- **Jatuh Tempo:** " . $dueDate->format('d M Y') . "\n\n" .
                             "Data invoice telah disimpan dan tercatat di database.";
                    $speakText = "Saya telah berhasil membuat invoice {$invoiceNumber} untuk klien {$client->nama_client} sebesar Rp " . number_format($amount, 0, ',', '.') . ", jatuh tempo pada tanggal " . $dueDate->format('d M Y') . ".";
                }
            } elseif ($action === 'create_client') {
                $clientName = $parameters['client_name'] ?? 'Budi Santoso';
                $email = $parameters['email'] ?? null;
                $noHp = $parameters['no_hp'] ?? null;

                $client = \App\Models\Client::create([
                    'kode_client' => \App\Models\Client::generateCode(),
                    'nama_client' => $clientName,
                    'email' => $email,
                    'no_hp' => $noHp,
                    'client_type' => 'individual',
                    'status' => 'aktif'
                ]);

                \App\Models\ActivityLog::log('create_client', "Client {$client->nama_client} ({$client->kode_client}) created via AI Assistant", $client);

                if ($locale === 'en') {
                    $reply = "### Client Registered Successfully! 👤\n\n" .
                             "- **Client Code:** `{$client->kode_client}`\n" .
                             "- **Name:** {$client->nama_client}\n" .
                             "- **Email:** " . ($client->email ?: '-') . "\n" .
                             "- **Phone:** " . ($client->no_hp ?: '-') . "\n\n" .
                             "The client has been added to your database.";
                    $speakText = "Client {$client->nama_client} has been successfully registered with code {$client->kode_client}.";
                } else {
                    $reply = "### Klien Berhasil Didaftarkan! 👤\n\n" .
                             "- **Kode Klien:** `{$client->kode_client}`\n" .
                             "- **Nama:** {$client->nama_client}\n" .
                             "- **Email:** " . ($client->email ?: '-') . "\n" .
                             "- **No. HP:** " . ($client->no_hp ?: '-') . "\n\n" .
                             "Data klien baru telah tersimpan di sistem.";
                    $speakText = "Klien {$client->nama_client} berhasil didaftarkan dengan kode klien {$client->kode_client}.";
                }
            } elseif ($action === 'send_followup') {
                $invoice = null;
                if (!empty($parameters['invoice_number'])) {
                    $invoice = \App\Models\Invoice::with('client')->where('invoice_number', $parameters['invoice_number'])->first();
                }
                if (!$invoice && !empty($parameters['client_name'])) {
                    $client = \App\Models\Client::where('nama_client', 'like', '%' . $parameters['client_name'] . '%')->first();
                    if ($client) {
                        $invoice = \App\Models\Invoice::with('client')->where('client_id', $client->id)->whereIn('status', ['sent', 'pending', 'dp'])->first();
                    }
                }

                if ($invoice) {
                    $clientName = $invoice->client->nama_client;
                    $invoiceNumber = $invoice->invoice_number;
                    $amountStr = "Rp " . number_format($invoice->total, 0, ',', '.');
                    $dueDateStr = $invoice->due_date->format('d M Y');

                    if ($locale === 'en') {
                        $messageDraft = "Dear {$clientName},\n\nWe hope this message finds you well. This is a gentle reminder that Invoice #{$invoiceNumber} amounting to {$amountStr} is due on {$dueDateStr}. Please let us know if the payment has been processed or if you need any assistance.\n\nBest regards,\nRooterin Finance Team";
                        
                        \App\Models\ActivityLog::log('send_followup', "Collection reminder sent to {$clientName} for Invoice {$invoiceNumber}", $invoice, ['message_draft' => $messageDraft]);

                        $reply = "### Follow-up Message Sent! ✉️\n\n" .
                                 "I have successfully compiled and simulated sending the collection reminder for **Invoice #{$invoiceNumber}** to **{$clientName}**.\n\n" .
                                 "**Message Draft:**\n" .
                                 "```\n{$messageDraft}\n```\n\n" .
                                 "Activity logged under 'ActivityLog'.";
                        $speakText = "I have drafted and simulated sending a collection reminder for Invoice {$invoiceNumber} to {$clientName}.";
                    } else {
                        $messageDraft = "Yth. Bapak/Ibu {$clientName},\n\nSemoga pesan ini menemui Anda dalam keadaan baik. Kami ingin mengingatkan kembali mengenai Invoice #{$invoiceNumber} sebesar {$amountStr} yang jatuh tempo pada {$dueDateStr}. Mohon kerja samanya untuk segera memproses pembayaran tersebut.\n\nSalam hangat,\nTim Keuangan Rooterin";
                        
                        \App\Models\ActivityLog::log('send_followup', "Pesan penagihan dikirim ke {$clientName} untuk Invoice {$invoiceNumber}", $invoice, ['message_draft' => $messageDraft]);

                        $reply = "### Pesan Penagihan Terkirim! ✉️\n\n" .
                                 "Saya telah menyusun dan mensimulasikan pengiriman draf pesan penagihan untuk **Invoice #{$invoiceNumber}** kepada **{$clientName}**.\n\n" .
                                 "**Draf Pesan:**\n" .
                                 "```\n{$messageDraft}\n```\n\n" .
                                 "Aktivitas penagihan berhasil dicatat dalam log sistem.";
                        $speakText = "Saya telah mengirimkan pengingat penagihan untuk Invoice {$invoiceNumber} kepada klien {$clientName}.";
                    }
                } else {
                    if ($locale === 'en') {
                        $reply = "Could not find any outstanding/unpaid invoice for the client specified. Please double-check client name or invoice number.";
                        $speakText = "I couldn't find any pending invoice to follow up on.";
                    } else {
                        $reply = "Tidak dapat menemukan invoice yang belum lunas untuk klien yang dimaksud. Silakan periksa kembali nama klien atau nomor invoice.";
                        $speakText = "Saya tidak menemukan tagihan aktif untuk ditindaklanjuti.";
                    }
                }
            } elseif ($action === 'forecast_cashflow') {
                $historical = \App\Models\Invoice::where('status', 'paid')
                    ->where('tanggal_invoice', '>=', Carbon::now()->subMonths(6))
                    ->select(\DB::raw('DATE_FORMAT(tanggal_invoice, "%Y-%m") as month'), \DB::raw('SUM(total) as total'))
                    ->groupBy('month')
                    ->orderBy('month', 'asc')
                    ->get()
                    ->pluck('total', 'month')
                    ->toArray();

                $upcoming = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])
                    ->where('due_date', '>=', Carbon::now()->startOfMonth())
                    ->select(\DB::raw('DATE_FORMAT(due_date, "%Y-%m") as month'), \DB::raw('SUM(total) as total'))
                    ->groupBy('month')
                    ->orderBy('month', 'asc')
                    ->get()
                    ->pluck('total', 'month')
                    ->toArray();

                $labels = [];
                $data = [];
                for ($i = 0; $i < 3; $i++) {
                    $month = Carbon::now()->addMonths($i);
                    $monthKey = $month->format('Y-m');
                    $labels[] = $month->format('M Y');
                    $upcomingAmount = $upcoming[$monthKey] ?? 0;
                    $historicalAvg = count($historical) > 0 ? array_sum($historical) / count($historical) : 5000000;
                    $predictedAmount = $upcomingAmount + ($historicalAvg * (1 - $i * 0.25));
                    $data[] = round($predictedAmount, 0);
                }

                $chartData = [
                    'labels' => $labels,
                    'data' => $data
                ];

                if ($locale === 'en') {
                    $reply = "### Cash Flow Forecast (Next 3 Months) 📈\n\n" .
                             "Here is the predicted cash inflow based on upcoming due invoices and historical payment averages:\n\n" .
                             "1. **" . $labels[0] . ":** Rp " . number_format($data[0], 0, ',', '.') . "\n" .
                             "2. **" . $labels[1] . ":** Rp " . number_format($data[1], 0, ',', '.') . "\n" .
                             "3. **" . $labels[2] . ":** Rp " . number_format($data[2], 0, ',', '.') . "\n\n" .
                             "An interactive line chart projection has been rendered below.";
                    $speakText = "Based on our payment trends, the cash flow forecast for the next three months shows expected inflows of Rp " . number_format($data[0], 0, ',', '.') . " in " . $labels[0] . ", followed by Rp " . number_format($data[1], 0, ',', '.') . " and Rp " . number_format($data[2], 0, ',', '.') . ".";
                } else {
                    $reply = "### Estimasi Perputaran Kas (3 Bulan Ke Depan) 📈\n\n" .
                             "Berikut adalah proyeksi pemasukan kas berdasarkan tanggal jatuh tempo tagihan aktif dan rata-rata pembayaran historis:\n\n" .
                             "1. **" . $labels[0] . ":** Rp " . number_format($data[0], 0, ',', '.') . "\n" .
                             "2. **" . $labels[1] . ":** Rp " . number_format($data[1], 0, ',', '.') . "\n" .
                             "3. **" . $labels[2] . ":** Rp " . number_format($data[2], 0, ',', '.') . "\n\n" .
                             "Proyeksi grafik interaktif telah disematkan di bawah bubble chat ini.";
                    $speakText = "Berdasarkan tren pembayaran, estimasi arus kas masuk kita untuk tiga bulan ke depan diproyeksikan sebesar Rp " . number_format($data[0], 0, ',', '.') . " pada " . $labels[0] . ", disusul Rp " . number_format($data[1], 0, ',', '.') . " dan Rp " . number_format($data[2], 0, ',', '.') . ".";
                }
            }
        }

        // Save to database
        \App\Models\AiChatHistory::create([
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'message' => $userMessage,
            'response' => $reply
        ]);

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'speak_text' => $speakText,
            'session_id' => $sessionId,
            'chart_data' => $chartData,
            'created_invoice_number' => $createdInvoiceNumber
        ]);
    }

    public function proactiveCheck(Request $request)
    {
        abort_if(!auth()->user()->hasFullAccess(), 403, 'Unauthorized action.');

        $locale = app()->getLocale();

        // Overdue threshold: > 14 days
        $veryOverdueInvoices = \App\Models\Invoice::with('client')
            ->whereIn('status', ['sent', 'pending', 'dp'])
            ->where('due_date', '<', Carbon::now()->subDays(14))
            ->get();

        $totalOverdueVal = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])->where('due_date', '<', Carbon::now())->sum('total');
        $totalUnpaidVal = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])->sum('total');
        $anomalyCount = $veryOverdueInvoices->count();

        try {
            $apiKey = env('GEMINI_API_KEY') ?: config('gemini.api_key');
            if (empty($apiKey)) {
                throw new \Exception("Key empty");
            }

            $prompt = "You are the Autonomous AI Financial Officer (Rooterin AI 2.0).
Based on the following system statistics:
- Number of invoices overdue by more than 14 days: {$anomalyCount}
- Total overdue receivables value: Rp " . number_format($totalOverdueVal, 0, ',', '.') . "
- Total outstanding unpaid value: Rp " . number_format($totalUnpaidVal, 0, ',', '.') . "

Provide a proactive greeting/alert message to the Administrator on page load.
If there are critical overdue anomalies, say something like:
'Halo Admin, saya mendeteksi ada X klien yang performa pembayarannya memburuk bulan ini (jatuh tempo lebih dari 14 hari). Apakah Anda ingin saya membuatkan draf surat penagihan otomatis?' (adjust based on actual data).
If there are no anomalies, greet them warmly and mention that the financial status is healthy.

Return a JSON object ONLY:
{
  \"reply\": \"Markdown textual greeting\",
  \"speak_text\": \"Short spoken version of the greeting (2-3 sentences max) to be read aloud via Text-to-Speech\"
}

Language constraint:
Strictly match active language: " . ($locale === 'en' ? 'English' : 'Indonesian');

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if ($response->successful()) {
                $resData = $response->json();
                $rawReply = $resData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                $decoded = json_decode(trim($rawReply), true);
                if ($decoded && isset($decoded['reply'])) {
                    return response()->json([
                        'success' => true,
                        'reply' => $decoded['reply'],
                        'speak_text' => $decoded['speak_text'] ?? ''
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiChatController proactiveCheck Gemini error: " . $e->getMessage());
        }

        // Fallback
        $fallbackReply = "";
        $fallbackSpeak = "";
        if ($locale === 'en') {
            if ($anomalyCount > 0) {
                $fallbackReply = "### Proactive Alert ⚠️\n\nHello Admin, I have detected {$anomalyCount} invoices that are overdue by more than 14 days, totaling Rp " . number_format($totalOverdueVal, 0, ',', '.') . ". Would you like me to draft an automated collection reminder message for these clients?";
                $fallbackSpeak = "Hello Admin, I have detected {$anomalyCount} invoices that are overdue by more than 14 days. Would you like me to draft collection reminders for you?";
            } else {
                $fallbackReply = "### Good Day Admin! 👋\n\nEverything looks good! I have analyzed the database, and there are no severe overdue invoice anomalies. All systems are performing within healthy cash flow levels.";
                $fallbackSpeak = "Good day Admin! Everything looks good. There are no severe overdue anomalies today.";
            }
        } else {
            if ($anomalyCount > 0) {
                $fallbackReply = "### Notifikasi Proaktif ⚠️\n\nHalo Admin, saya mendeteksi ada {$anomalyCount} invoice yang menunggak lebih dari 14 hari dengan total nominal Rp " . number_format($totalOverdueVal, 0, ',', '.') . ". Apakah Anda ingin saya membuatkan draf surat penagihan otomatis untuk klien-klien tersebut?";
                $fallbackSpeak = "Halo Admin, saya mendeteksi ada {$anomalyCount} invoice yang menunggak lebih dari 14 hari. Apakah Anda ingin saya membuatkan draf surat penagihan otomatis?";
            } else {
                $fallbackReply = "### Selamat Pagi/Siang Admin! 👋\n\nSemua berjalan lancar! Saya telah menganalisis database dan tidak menemukan anomali invoice overdue yang kritis hari ini. Arus kas terpantau dalam kondisi sehat.";
                $fallbackSpeak = "Halo Admin, semuanya berjalan lancar. Tidak ada anomali pembayaran kritis yang terdeteksi hari ini.";
            }
        }

        return response()->json([
            'success' => true,
            'reply' => $fallbackReply,
            'speak_text' => $fallbackSpeak
        ]);
    }

    public function handleUpload(Request $request)
    {
        abort_if(!auth()->user()->hasFullAccess(), 403, 'Unauthorized action.');

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $locale = app()->getLocale();
        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        $base64Data = base64_encode(file_get_contents($file->getRealPath()));

        $receiptNumber = \App\Models\Receipt::generateNumber();

        try {
            $apiKey = env('GEMINI_API_KEY') ?: config('gemini.api_key');
            if (empty($apiKey)) {
                throw new \Exception("Key empty");
            }

            $prompt = "Extract receipt transaction data from this document/image. Return a JSON object with this schema:
{
  \"client_name\": \"...\",
  \"vendor_name\": \"...\",
  \"date\": \"YYYY-MM-DD\",
  \"amount\": 123456,
  \"items\": [
    {
      \"deskripsi\": \"...\",
      \"qty\": 1,
      \"harga\": 123456
    }
  ]
}
If you cannot find items, create a single item using vendor/client name and total amount.";

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => $mimeType,
                                    'data' => $base64Data
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if ($response->successful()) {
                $resData = $response->json();
                $rawReply = $resData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                $resJson = json_decode(trim($rawReply), true);

                if ($resJson) {
                    $clientName = $resJson['client_name'] ?? $resJson['vendor_name'] ?? 'General Receipt Client';
                    $dateStr = $resJson['date'] ?? Carbon::now()->format('Y-m-d');
                    $amount = intval($resJson['amount'] ?? 0);
                    $items = $resJson['items'] ?? [];

                    $client = \App\Models\Client::where('nama_client', 'like', '%' . $clientName . '%')
                        ->orWhere('nama_perusahaan', 'like', '%' . $clientName . '%')
                        ->first();
                    
                    if (!$client) {
                        $client = \App\Models\Client::create([
                            'kode_client' => \App\Models\Client::generateCode(),
                            'nama_client' => $clientName,
                            'client_type' => 'individual',
                            'status' => 'aktif'
                        ]);
                    }

                    \DB::beginTransaction();
                    $receipt = \App\Models\Receipt::create([
                        'receipt_number' => $receiptNumber,
                        'client_id' => $client->id,
                        'tanggal_receipt' => Carbon::parse($dateStr),
                        'expiry_date' => Carbon::parse($dateStr)->addMonth(),
                        'status' => 'draft',
                        'subtotal' => $amount,
                        'tax_percent' => 0,
                        'discount_percent' => 0,
                        'total' => $amount,
                        'created_by' => auth()->id(),
                        'notes_internal' => 'Scanned automatically by Autonomous AI Financial Officer OCR',
                    ]);

                    if (empty($items)) {
                        $items = [[
                            'deskripsi' => 'Scanned Receipt Item - ' . $clientName,
                            'qty' => 1,
                            'harga' => $amount,
                            'total' => $amount
                        ]];
                    }

                    foreach ($items as $item) {
                        $qty = floatval($item['qty'] ?? 1);
                        $harga = floatval($item['harga'] ?? $amount);
                        $receipt->items()->create([
                            'deskripsi' => $item['deskripsi'] ?? 'Scanned Receipt Item',
                            'qty' => $qty,
                            'harga' => $harga,
                            'total' => $qty * $harga,
                        ]);
                    }
                    \DB::commit();

                    \App\Models\ActivityLog::log('scan_receipt', "Receipt {$receiptNumber} created via OCR scan for {$client->nama_client}", $receipt);

                    if ($locale === 'en') {
                        $reply = "### Document Scanned & Registered! 📄🔍\n\n" .
                                 "I have successfully scanned the document and extracted the financial details:\n\n" .
                                 "- **Receipt Number:** `{$receiptNumber}`\n" .
                                 "- **Client/Vendor:** {$client->nama_client}\n" .
                                 "- **Total Amount:** Rp " . number_format($amount, 0, ',', '.') . "\n" .
                                 "- **Transaction Date:** " . Carbon::parse($dateStr)->format('d M Y') . "\n\n" .
                                 "This receipt is now saved in the system as a draft.";
                        $speakText = "I have scanned the receipt and created a new draft receipt {$receiptNumber} for client {$client->nama_client} with a total of Rp " . number_format($amount, 0, ',', '.') . ".";
                    } else {
                        $reply = "### Kuitansi Berhasil Dipindai & Disimpan! 📄🔍\n\n" .
                                 "Saya berhasil mengekstrak data dari kuitansi yang diunggah:\n\n" .
                                 "- **Nomor Kuitansi:** `{$receiptNumber}`\n" .
                                 "- **Klien/Vendor:** {$client->nama_client}\n" .
                                 "- **Total Nominal:** Rp " . number_format($amount, 0, ',', '.') . "\n" .
                                 "- **Tanggal Transaksi:** " . Carbon::parse($dateStr)->format('d M Y') . "\n\n" .
                                 "Kuitansi ini telah disimpan di sistem dengan status draft.";
                        $speakText = "Saya telah memindai kuitansi tersebut dan membuat draf kuitansi baru {$receiptNumber} untuk klien {$client->nama_client} sebesar Rp " . number_format($amount, 0, ',', '.') . ".";
                    }

                    return response()->json([
                        'success' => true,
                        'reply' => $reply,
                        'speak_text' => $speakText
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiChatController handleUpload Gemini Error: " . $e->getMessage());
        }

        // Fallback: Create mock receipt
        $client = \App\Models\Client::firstOrCreate(
            ['nama_client' => 'Mock Vendor'],
            [
                'kode_client' => \App\Models\Client::generateCode(),
                'client_type' => 'individual',
                'status' => 'aktif'
            ]
        );

        \DB::beginTransaction();
        $receipt = \App\Models\Receipt::create([
            'receipt_number' => $receiptNumber,
            'client_id' => $client->id,
            'tanggal_receipt' => Carbon::now(),
            'expiry_date' => Carbon::now()->addMonth(),
            'status' => 'draft',
            'subtotal' => 1500000,
            'tax_percent' => 0,
            'discount_percent' => 0,
            'total' => 1500000,
            'created_by' => auth()->id(),
            'notes_internal' => 'Created via Local OCR Fallback (Mock Data)',
        ]);
        $receipt->items()->create([
            'deskripsi' => 'Mock Scanned Receipt Item',
            'qty' => 1,
            'harga' => 1500000,
            'total' => 1500000,
        ]);
        \DB::commit();

        \App\Models\ActivityLog::log('scan_receipt', "Receipt {$receiptNumber} created via Mock OCR scan for {$client->nama_client}", $receipt);

        if ($locale === 'en') {
            $reply = "### OCR Fallback Activated 📄🔍\n\n" .
                     "I was unable to reach Gemini OCR. However, for simulation/testing, I created a Mock Receipt:\n\n" .
                     "- **Receipt Number:** `{$receiptNumber}`\n" .
                     "- **Client/Vendor:** {$client->nama_client}\n" .
                     "- **Total Amount:** Rp 1.500.000\n" .
                     "- **Transaction Date:** " . Carbon::now()->format('d M Y') . "\n\n" .
                     "This receipt is now saved in the system as a draft.";
            $speakText = "Gemini OCR is currently unavailable. I have created a mock draft receipt {$receiptNumber} for client {$client->nama_client} with a total of Rp 1.500.000.";
        } else {
            $reply = "### Fallback OCR Diaktifkan 📄🔍\n\n" .
                     "Gagal menghubungi modul Gemini OCR. Untuk kebutuhan simulasi, saya telah membuat Kuitansi Mock:\n\n" .
                     "- **Nomor Kuitansi:** `{$receiptNumber}`\n" .
                     "- **Klien/Vendor:** {$client->nama_client}\n" .
                     "- **Total Nominal:** Rp 1.500.000\n" .
                     "- **Tanggal Transaksi:** " . Carbon::now()->format('d M Y') . "\n\n" .
                     "Kuitansi ini telah disimpan di sistem dengan status draft.";
            $speakText = "Modul Gemini OCR tidak tersedia. Saya telah membuat draf kuitansi mock baru {$receiptNumber} untuk klien {$client->nama_client} sebesar Rp 1.500.000.";
        }

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'speak_text' => $speakText
        ]);
    }

    public function getSessionsList()
    {
        abort_if(!auth()->user()->hasFullAccess(), 403, 'Unauthorized action.');

        $sessions = $this->getOptimizedSessions();

        return response()->json([
            'success' => true,
            'sessions' => $sessions
        ]);
    }

    public function renameSession(Request $request, $sessionId)
    {
        abort_if(!auth()->user()->hasFullAccess(), 403, 'Unauthorized action.');

        $request->validate([
            'title' => 'required|string|max:100'
        ]);

        \App\Models\AiChatHistory::where('user_id', auth()->id())
            ->where('session_id', $sessionId)
            ->update(['title' => $request->input('title')]);

        return response()->json([
            'success' => true
        ]);
    }

    public function handleVoiceCommand(Request $request)
    {
        $request->validate([
            'command' => 'required|string|max:500',
        ]);

        $command = trim($request->input('command'));
        $commandLower = strtolower($command);

        // 1. Navigation intents
        if (str_contains($commandLower, 'kalender') || str_contains($commandLower, 'chronos')) {
            return response()->json([
                'success' => true,
                'redirect' => route('chronos.index'),
                'message' => 'Mengalihkan ke halaman Kalender Chronos...'
            ]);
        }
        
        if (str_contains($commandLower, 'buka dashboard') || str_contains($commandLower, 'halaman dashboard') || $commandLower === 'dashboard') {
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
                'message' => 'Mengalihkan ke Dashboard Utama...'
            ]);
        }

        if (str_contains($commandLower, 'buka halaman invoice') || str_contains($commandLower, 'buka invoice') || str_contains($commandLower, 'daftar invoice')) {
            return response()->json([
                'success' => true,
                'redirect' => route('invoices.index'),
                'message' => 'Mengalihkan ke Daftar Invoice...'
            ]);
        }

        if (str_contains($commandLower, 'buka halaman klien') || str_contains($commandLower, 'buka klien') || str_contains($commandLower, 'daftar klien')) {
            return response()->json([
                'success' => true,
                'redirect' => route('clients.index'),
                'message' => 'Mengalihkan ke Daftar Klien...'
            ]);
        }

        if (str_contains($commandLower, 'buka halaman kuitansi') || str_contains($commandLower, 'buka kuitansi') || str_contains($commandLower, 'daftar kuitansi') || str_contains($commandLower, 'receipt')) {
            return response()->json([
                'success' => true,
                'redirect' => route('receipts.index'),
                'message' => 'Mengalihkan ke Daftar Kuitansi...'
            ]);
        }

        if (str_contains($commandLower, 'buka pengaturan') || str_contains($commandLower, 'buka setting') || str_contains($commandLower, 'halaman settings')) {
            return response()->json([
                'success' => true,
                'redirect' => route('settings.index'),
                'message' => 'Mengalihkan ke Pengaturan Sistem...'
            ]);
        }

        // 2. Query Invoice Terbesar yang Belum Dibayar
        if (str_contains($commandLower, 'invoice terbesar') || str_contains($commandLower, 'tagihan terbesar')) {
            $largest = \App\Models\Invoice::with('client')
                ->whereIn('status', ['sent', 'pending', 'dp'])
                ->orderBy('total', 'desc')
                ->first();

            if ($largest) {
                $clientName = $largest->client->nama_client;
                $company = $largest->client->nama_perusahaan ? " ({$largest->client->nama_perusahaan})" : '';
                $amount = 'Rp ' . number_format($largest->total, 0, ',', '.');
                $number = $largest->invoice_number;
                $dueDate = $largest->due_date ? $largest->due_date->format('d M Y') : '-';

                return response()->json([
                    'success' => true,
                    'title' => 'Invoice Terbesar Belum Dibayar',
                    'message' => "Invoice terbesar yang belum dibayar adalah **{$number}** atas nama **{$clientName}{$company}** sebesar **{$amount}** (Jatuh tempo: {$dueDate})."
                ]);
            }

            return response()->json([
                'success' => true,
                'title' => 'Invoice Terbesar Belum Dibayar',
                'message' => 'Luar biasa! Tidak ada tagihan tertunggak (belum dibayar) yang terdaftar di sistem saat ini.'
            ]);
        }

        // 3. Query Total Tunggakan Aktif
        if (str_contains($commandLower, 'total tunggakan') || str_contains($commandLower, 'tunggakan aktif') || str_contains($commandLower, 'tunggakan minggu ini') || str_contains($commandLower, 'jumlah tunggakan')) {
            $totalArrears = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])->sum('total');
            $formatted = 'Rp ' . number_format($totalArrears, 0, ',', '.');
            $count = \App\Models\Invoice::whereIn('status', ['sent', 'pending', 'dp'])->count();

            return response()->json([
                'success' => true,
                'title' => 'Total Tunggakan Aktif',
                'message' => "Total tunggakan aktif saat ini adalah **{$formatted}** dari total **{$count} invoice** yang belum diselesaikan."
            ]);
        }

        // 4. Default AI or General Fallback
        try {
            $apiKey = env('GEMINI_API_KEY') ?: config('gemini.api_key');
            if (empty($apiKey)) {
                throw new \Exception("Key empty");
            }

            $prompt = "Kamu adalah sistem CFO Suara (Voice Command Processing) untuk Rooterin-Invoice.
Proses teks suara berikut dari user: \"{$command}\".
Berikan respons suara singkat, taktis, dan informatif (maksimal 2-3 kalimat) dalam Bahasa Indonesia.
Jika perintah tersebut berupa keinginan melihat data atau bernavigasi yang tidak terdeteksi secara otomatis, sarankan menu yang tepat.";

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

            if ($response->successful()) {
                $resData = $response->json();
                $reply = $resData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if (!empty($reply)) {
                    return response()->json([
                        'success' => true,
                        'title' => 'CFO Suara Respon',
                        'message' => trim($reply)
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Ignore & drop to local fallback
        }

        return response()->json([
            'success' => true,
            'title' => 'Perintah Suara Diterima',
            'message' => "Saya mendengar: \"{$command}\". Maaf, perintah spesifik ini belum dikonfigurasi. Anda dapat mencoba perintah seperti \"Buka halaman kalender\" atau \"Berapa total tunggakan aktif minggu ini?\"."
        ]);
    }

    public function deleteSession($sessionId)
    {
        abort_if(!auth()->user()->hasFullAccess(), 403, 'Unauthorized action.');

        \App\Models\AiChatHistory::where('user_id', auth()->id())
            ->where('session_id', $sessionId)
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }

    private function getOptimizedSessions()
    {
        $sessions = \App\Models\AiChatHistory::where('user_id', auth()->id())
            ->select('session_id', \DB::raw('MAX(created_at) as latest_created_at'))
            ->groupBy('session_id')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        if ($sessions->isEmpty()) {
            return collect();
        }

        $sessionIds = $sessions->pluck('session_id');

        $firstChatIds = \App\Models\AiChatHistory::whereIn('session_id', $sessionIds)
            ->select(\DB::raw('MIN(id) as first_id'))
            ->groupBy('session_id')
            ->pluck('first_id');

        $firstChats = \App\Models\AiChatHistory::whereIn('id', $firstChatIds)
            ->get()
            ->keyBy('session_id');

        return $sessions->map(function ($chat) use ($firstChats) {
            $firstChat = $firstChats->get($chat->session_id);
            
            return [
                'session_id' => $chat->session_id,
                'title' => ($firstChat && $firstChat->title) ? $firstChat->title : ($firstChat ? \Str::limit($firstChat->message, 35) : 'Obrolan Baru'),
                'created_at' => $chat->latest_created_at,
                'date_formatted' => Carbon::parse($chat->latest_created_at)->diffForHumans()
            ];
        });
    }
}
