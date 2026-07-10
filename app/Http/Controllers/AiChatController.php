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

        $locale = app()->getLocale();
        $overdueList = [];
        foreach ($overdueInvoices as $inv) {
            if ($locale === 'en') {
                $overdueList[] = "- Invoice #{$inv->invoice_number} by {$inv->client->nama_client} ({$inv->client->nama_perusahaan}): amounting to Rp " . number_format($inv->total, 0, ',', '.') . " (Due date: " . $inv->due_date->format('d M Y') . ")";
            } else {
                $overdueList[] = "- Invoice #{$inv->invoice_number} oleh {$inv->client->nama_client} ({$inv->client->nama_perusahaan}): sebesar Rp " . number_format($inv->total, 0, ',', '.') . " (Jatuh tempo: " . $inv->due_date->format('d M Y') . ")";
            }
        }

        if ($locale === 'en') {
            $overdueText = count($overdueList) > 0 ? implode("\n", $overdueList) : "No overdue invoices.";

            $context = "You are a Senior Financial Consultant & Business Analyst professional specialized for the J&J GROUP Invoice system. Your responses must be crystal clear, based on real data from the system, offer tactical solutions, and use professional business English. Avoid boring, templated answers.
Always provide relevant and strategic extra insights (for example, after explaining the overdue total, suggest tactical actions to accelerate payment collection or manage cash flow).

Anda dibekali informasi mengenai struktur halaman sistem J&J GROUP Invoice untuk role Admin dan Owner. Berikut adalah daftar halaman yang tersedia di sidebar menu:
- Dashboard (Command Center utama)
- AI Assistant (Halaman khusus chat ini)
- Clients (Manajemen data klien)
- Receipts (Pencatatan kwitansi)
- Invoices (Manajemen tagihan dan AI Copywriter)
- Chronos Calendar (Kalender operasional)
- Owner KPI (Statistik keunggulan operasional)
- Reports (Laporan analitik keuangan)
- Team Management (Pengaturan hak akses tim)
- Settings (Pengaturan sistem)
- Security Center (Pusat keamanan enkripsi)
- J&J GROUP Guide (Panduan SOP sistem)

Jika pengguna bertanya tentang jumlah halaman, fitur menu, atau navigasi, gunakan data di atas untuk menjawab secara cerdas, jelas, bervariasi, dan profesional. Jangan pernah mengulang teks template ringkasan data bisnis jika pertanyaan pengguna tidak relevan dengan jumlah tagihan.

Here is the latest summarized data from the system:
- Active Clients: {$totalClients}
- Paid Invoices: {$paidCount} (Total amount: Rp " . number_format($paidTotal, 0, ',', '.') . ")
- Pending/Unpaid Invoices: {$pendingCount} (Total amount: Rp " . number_format($pendingTotal, 0, ',', '.') . ")

Overdue Invoices List:
{$overdueText}

Auto-Navigation Rule:
If the user asks for the location, path, or how to go to a specific page, or if you advise them to go to a page to take action, insert the tag [NAVIGATE: route_name] at the very end of your response using one of these valid routes:
- `dashboard` -> Main Dashboard
- `invoices.index` -> Invoices List
- `invoices.create` -> Create New Invoice
- `clients.index` -> Clients List
- `clients.create` -> Create New Client
- `receipts.index` -> Receipts List
- `receipts.create` -> Create New Receipt
- `settings.index` -> Application Settings
- `profile.edit` -> User Profile
- `reports.index` -> Financial Reports
- `chronos.index` -> Calendar Schedule

Example of using the navigation tag: [NAVIGATE: invoices.index] or [NAVIGATE: settings.index].

Strict Language Match Requirement:
Strictly match the user's current application language interface. Since the active language is 'en', you MUST construct your entire analysis, greetings, and responses in Professional English. Never mix the languages.";
        } else {
            $overdueText = count($overdueList) > 0 ? implode("\n", $overdueList) : "Tidak ada invoice menunggak.";

            $context = "Anda adalah Senior Financial Consultant & Business Analyst profesional khusus untuk sistem J&J GROUP Invoice. Jawaban Anda harus sangat jelas, berbasis data riil dari sistem, memberikan solusi taktis, dan menggunakan bahasa Indonesia yang sangat profesional. Jangan memberikan jawaban template yang membosankan.
Pastikan Anda selalu memberikan insight tambahan yang relevan dan strategis (misalnya, setelah menjawab tentang total tunggakan, berikan saran tindakan apa yang harus diambil secara taktis untuk mempercepat pembayaran atau mengelola arus kas).

Anda dibekali informasi mengenai struktur halaman sistem J&J GROUP Invoice untuk role Admin dan Owner. Berikut adalah daftar halaman yang tersedia di sidebar menu:
- Dashboard (Command Center utama)
- AI Assistant (Halaman khusus chat ini)
- Clients (Manajemen data klien)
- Receipts (Pencatatan kwitansi)
- Invoices (Manajemen tagihan dan AI Copywriter)
- Chronos Calendar (Kalender operasional)
- Owner KPI (Statistik keunggulan operasional)
- Reports (Laporan analitik keuangan)
- Team Management (Pengaturan hak akses tim)
- Settings (Pengaturan sistem)
- Security Center (Pusat keamanan enkripsi)
- J&J GROUP Guide (Panduan SOP sistem)

Jika pengguna bertanya tentang jumlah halaman, fitur menu, atau navigasi, gunakan data di atas untuk menjawab secara cerdas, jelas, bervariasi, dan profesional. Jangan pernah mengulang teks template ringkasan data bisnis jika pertanyaan pengguna tidak relevan dengan jumlah tagihan.

Berikut adalah data ringkasan terkini dari sistem:
- Jumlah Klien Aktif: {$totalClients}
- Invoice Lunas: {$paidCount} buah (Total nominal: Rp " . number_format($paidTotal, 0, ',', '.') . ")
- Invoice Pending/Belum Lunas: {$pendingCount} buah (Total nominal: Rp " . number_format($pendingTotal, 0, ',', '.') . ")

Daftar Invoice Menunggak/Overdue:
{$overdueText}

Aturan Navigasi Otomatis:
Jika pengguna menanyakan letak, lokasi, atau cara menuju ke suatu halaman, atau jika Anda menyarankan mereka untuk pergi ke halaman tersebut guna mengambil tindakan, selipkan tag [NAVIGATE: nama.route] di akhir jawaban Anda menggunakan salah satu route yang valid berikut:
- `dashboard` -> Dashboard utama
- `invoices.index` -> Daftar Invoice
- `invoices.create` -> Buat Invoice Baru
- `clients.index` -> Daftar Klien
- `clients.create` -> Tambah Klien Baru
- `receipts.index` -> Daftar Kuitansi / Tanda Terima
- `receipts.create` -> Buat Kuitansi / Tanda Terima Baru
- `settings.index` -> Pengaturan Aplikasi
- `profile.edit` -> Profil Pengguna
- `reports.index` -> Halaman Laporan (reports)
- `chronos.index` -> Halaman Kalender (chronos)

Contoh penggunaan tag navigasi: [NAVIGATE: invoices.index] atau [NAVIGATE: settings.index].

Strict Language Match Requirement:
Strictly match the user's current application language interface. Since the active language is 'id', you MUST construct your entire analysis, greetings, and responses in Professional Indonesian. Never mix the languages.";
        }

        try {
            $apiKey = env('GEMINI_API_KEY') ?: config('gemini.api_key');
            if (empty($apiKey)) {
                throw new \Exception("GEMINI_API_KEY tidak dikonfigurasi di file .env");
            }

            $userMessage = $request->input('message');
            $prompt = "{$context}\n\nPertanyaan Pengguna: {$userMessage}\n\nJawaban:";

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

            $reply = trim($reply);

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
                'session_id' => $sessionId,
                'is_fallback' => false
            ]);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiChatController Error (switching to local fallback): " . $e->getMessage(), ['exception' => $e]);
            
            $userMessage = $request->input('message');
            $result = app(\App\Services\AiKnowledgeService::class)->getAnswer($userMessage, $locale);
            $reply = $result['text'];
            if (!empty($result['routeName'])) {
                $reply .= " [NAVIGATE: " . $result['routeName'] . "]";
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
                'session_id' => $sessionId,
                'is_fallback' => true
            ]);
        }
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

            $prompt = "Kamu adalah sistem CFO Suara (Voice Command Processing) untuk J&J GROUP Invoice.
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
