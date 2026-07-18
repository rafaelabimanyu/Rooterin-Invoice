<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\AiChatHistory;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Str;

class DashboardChatbot extends Component
{
    public $messages = [];
    public $input = '';
    public $sessionId;

    public function mount()
    {
        $this->sessionId = (string) Str::uuid();
        $this->initializeMessages();
    }

    public function initializeMessages()
    {
        $locale = app()->getLocale();
        $this->messages = [
            [
                'sender' => 'ai',
                'text' => $locale === 'en'
                    ? "Hello! I am your Executive Business Partner for J&J GROUP. Let's analyze our cash flows, overdue clients, revenue projections, or system modules today."
                    : "Halo! Saya Executive Business Partner J&J GROUP Anda. Mari kita analisis arus kas, klien overdue, proyeksi pendapatan, atau modul sistem hari ini.",
                'navigateUrl' => null,
                'navigateLabel' => null
            ]
        ];
    }

    public function sendMessage()
    {
        $userMessage = trim($this->input);
        if (empty($userMessage)) {
            return;
        }

        // Add user message to local state
        $this->messages[] = [
            'sender' => 'user',
            'text' => $userMessage,
            'navigateUrl' => null,
            'navigateLabel' => null
        ];

        // Reset the input field
        $this->input = '';

        // Call the AI response generation
        $this->getAiResponse($userMessage);
    }

    protected function getAiResponse($userMessage)
    {
        $totalClients = Client::where('status', 'aktif')->count();
        $paidCount = Invoice::where('status', 'paid')->count();
        $paidTotal = Invoice::where('status', 'paid')->sum('total');
        $pendingCount = Invoice::whereIn('status', ['sent', 'pending', 'dp'])->count();
        $pendingTotal = Invoice::whereIn('status', ['sent', 'pending', 'dp'])->sum('total');

        // Overdue invoices
        $overdueInvoices = Invoice::with('client')
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

            $context = "You are a strategic Executive Business Partner for the owner of J&J GROUP. Your responses must be sharp, analytical, extremely to-the-point, and free of conversational fluff.
When discussing or analyzing any system data or financial queries, you MUST structure your response strictly as follows:
[Analisis Data]
(Provide exact figures and numerical analysis based on the live system data below)

[Dampak Bisnis]
(Explain the operational or financial impact and underlying causes for J&J GROUP)

[Rekomendasi Aksi]
(Give concrete, highly specific, and immediately actionable recommendation actions)

If the query is a greeting or a general navigational question, answer politely in a professional business executive tone.
If the query is about a topic you do not know or do not have data for, politely decline in an elegant consultant tone, explaining the boundaries of your expertise, and listing the core J&J GROUP topics you can assist with (e.g., invoice statuses, revenue trends, overdue clients, cash flows).

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
Strict match the user's current application language interface. Since the active language is 'en', you MUST construct your entire analysis, greetings, and responses in Professional English. Never mix the languages.";
        } else {
            $overdueText = count($overdueList) > 0 ? implode("\n", $overdueList) : "Tidak ada invoice menunggak.";

            $context = "Anda adalah Executive Business Partner strategis khusus untuk pemilik J&J GROUP. Jawaban Anda harus tajam, analitis, sangat langsung pada sasaran (to-the-point), dan bebas dari basa-basi.
Ketika membahas atau menganalisis data sistem atau kueri keuangan apa pun, Anda WAJIB menyusun respons Anda dengan format terstruktur persis seperti ini:
[Analisis Data]
(Sajikan data berupa angka riil dan analisis numerik berdasarkan data sistem di bawah)

[Dampak Bisnis]
(Jelaskan dampak operasional atau finansial serta penyebab mendasar bagi J&J GROUP)

[Rekomendasi Aksi]
(Berikan rekomendasi tindakan nyata yang spesifik dan langsung dapat dieksekusi)

Jika kueri berupa sapaan atau pertanyaan navigasi umum, jawab dengan sapaan sopan dalam nada bahasa eksekutif bisnis profesional yang to-the-point.
Jika kueri di luar topik yang Anda ketahui atau Anda tidak memiliki data untuk itu, tolak dengan sopan menggunakan gaya bahasa konsultan yang elegan, jelaskan batasan keahlian Anda, dan cantumkan daftar topik utama J&J GROUP yang dapat Anda bantu (seperti status invoice, tren pendapatan, klien menunggak, arus kas).

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
- `receipts.index` -> Daftar Kwitansi / Tanda Terima
- `receipts.create` -> Buat Kwitansi / Tanda Terima Baru
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

            $prompt = "{$context}\n\nPertanyaan Pengguna: {$userMessage}\n\nJawaban:";

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

            $reply = trim($reply);

            // Save to database
            AiChatHistory::create([
                'user_id' => auth()->id(),
                'session_id' => $this->sessionId,
                'message' => $userMessage,
                'response' => $reply
            ]);

            $this->messages[] = $this->processResponse($reply);

        } catch (\Throwable $e) {
            Log::error("DashboardChatbot Error (switching to local fallback): " . $e->getMessage(), ['exception' => $e]);

            $result = app(\App\Services\AiKnowledgeService::class)->getAnswer($userMessage, $locale);
            $reply = $result['text'];
            if (!empty($result['routeName'])) {
                $reply .= " [NAVIGATE: " . $result['routeName'] . "]";
            }

            // Save to database
            AiChatHistory::create([
                'user_id' => auth()->id(),
                'session_id' => $this->sessionId,
                'message' => $userMessage,
                'response' => $reply
            ]);

            $this->messages[] = $this->processResponse($reply);
        }
    }

    protected function processResponse($reply)
    {
        $routeName = null;
        $text = $reply;
        $navRegex = '/\[NAVIGATE:\s*([a-zA-Z0-9_\.-]+)\]/';

        if (preg_match($navRegex, $reply, $matches)) {
            $routeName = trim($matches[1]);
            $text = trim(preg_replace($navRegex, '', $reply));
        }

        $msg = [
            'sender' => 'ai',
            'text' => $text,
            'navigateUrl' => null,
            'navigateLabel' => null
        ];

        if ($routeName) {
            $routeMap = $this->getRouteMap();
            $locale = app()->getLocale();
            $routeLabels = $this->getRouteLabels($locale);

            if (isset($routeMap[$routeName])) {
                $msg['navigateUrl'] = $routeMap[$routeName];
                $msg['navigateLabel'] = $routeLabels[$routeName] ?? ($locale === 'en' ? '👉 Open Page' : '👉 Buka Halaman');
            }
        }

        return $msg;
    }

    protected function getRouteMap()
    {
        return [
            'dashboard' => route('dashboard'),
            'invoices.index' => route('invoices.index'),
            'invoices.create' => route('invoices.create'),
            'clients.index' => route('clients.index'),
            'clients.create' => route('clients.create'),
            'receipts.index' => route('receipts.index'),
            'receipts.create' => route('receipts.create'),
            'settings.index' => route('settings.index'),
            'profile.edit' => route('profile.edit'),
            'reports.index' => route('reports.index'),
            'chronos.index' => route('chronos.index'),
            'business-units.index' => route('business-units.index'),
            'users.index' => route('users.index'),
            'owner.kpi' => route('owner.kpi'),
            'security.center' => route('security.center'),
        ];
    }

    protected function getRouteLabels($locale)
    {
        if ($locale === 'en') {
            return [
                'dashboard' => "👉 Open Dashboard",
                'invoices.index' => "👉 View Invoices List",
                'invoices.create' => "👉 Create New Invoice",
                'clients.index' => "👉 View Clients List",
                'clients.create' => "👉 Add New Client",
                'receipts.index' => "👉 View Receipts List",
                'receipts.create' => "👉 Create New Receipt",
                'settings.index' => "👉 Open Settings",
                'profile.edit' => "👉 Edit My Profile",
                'reports.index' => "👉 View Financial Reports",
                'chronos.index' => "👉 Open Billing Calendar (Chronos)",
                'business-units.index' => "👉 View Business Units",
                'users.index' => "👉 Manage Team Users",
                'owner.kpi' => "👉 Open Owner KPI",
                'security.center' => "👉 Open Security Center",
            ];
        } else {
            return [
                'dashboard' => "👉 Buka Dashboard",
                'invoices.index' => "👉 Lihat Daftar Invoice",
                'invoices.create' => "👉 Buat Invoice Baru",
                'clients.index' => "👉 Lihat Daftar Klien",
                'clients.create' => "👉 Tambah Klien Baru",
                'receipts.index' => "👉 Lihat Daftar Kwitansi",
                'receipts.create' => "👉 Buat Kwitansi Baru",
                'settings.index' => "👉 Buka Pengaturan",
                'profile.edit' => "👉 Edit Profil Saya",
                'reports.index' => "👉 Lihat Laporan Keuangan",
                'chronos.index' => "👉 Buka Kalender Billing (Chronos)",
                'business-units.index' => "👉 Lihat Unit Bisnis",
                'users.index' => "👉 Kelola Pengguna Tim",
                'owner.kpi' => "👉 Buka KPI Owner",
                'security.center' => "👉 Buka Pusat Keamanan",
            ];
        }
    }

    public function render()
    {
        return view('livewire.dashboard-chatbot');
    }
}
