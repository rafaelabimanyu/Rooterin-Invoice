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
                    ? "Hello! I am Rooterin's Virtual Assistant. Is there anything I can help you with regarding billing, clients, or financial summary today?"
                    : "Halo! Saya Asisten Virtual Rooterin. Ada yang bisa saya bantu terkait tagihan, klien, atau ringkasan keuangan hari ini?",
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

            $context = "You are a Senior Financial Consultant & Business Analyst professional specialized for the Rooterin-Invoice system. Your responses must be crystal clear, based on real data from the system, offer tactical solutions, and use professional business English. Avoid boring, templated answers.
Always provide relevant and strategic extra insights (for example, after explaining the overdue total, suggest tactical actions to accelerate payment collection or manage cash flow).

Anda dibekali informasi mengenai struktur halaman sistem Rooterin-Invoice untuk role Admin dan Owner. Berikut adalah daftar halaman yang tersedia di sidebar menu:
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
- Rooterin Guide (Panduan SOP sistem)

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

            $context = "Anda adalah Senior Financial Consultant & Business Analyst profesional khusus untuk sistem Rooterin-Invoice. Jawaban Anda harus sangat jelas, berbasis data riil dari sistem, memberikan solusi taktis, dan menggunakan bahasa Indonesia yang sangat profesional. Jangan memberikan jawaban template yang membosankan.
Pastikan Anda selalu memberikan insight tambahan yang relevan dan strategis (misalnya, setelah menjawab tentang total tunggakan, berikan saran tindakan apa yang harus diambil secara taktis untuk mempercepat pembayaran atau mengelola arus kas).

Anda dibekali informasi mengenai struktur halaman sistem Rooterin-Invoice untuk role Admin dan Owner. Berikut adalah daftar halaman yang tersedia di sidebar menu:
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
- Rooterin Guide (Panduan SOP sistem)

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

            $userMsgLower = strtolower($userMessage);
            $reply = "";
            $navigateTag = "";

            if (str_contains($userMsgLower, 'halaman') || str_contains($userMsgLower, 'menu') || str_contains($userMsgLower, 'navigasi') || str_contains($userMsgLower, 'fitur') || str_contains($userMsgLower, 'role') || str_contains($userMsgLower, 'page')) {
                if ($locale === 'en') {
                    $reply = "The Rooterin-Invoice system provides 12 main pages/menu sections for the Admin and Owner roles:\n" .
                             "1. **Dashboard** (Main Command Center)\n" .
                             "2. **AI Assistant** (This dedicated chat assistant)\n" .
                             "3. **Clients** (Client data management)\n" .
                             "4. **Receipts** (Receipt records)\n" .
                             "5. **Invoices** (Invoice management and AI Copywriter)\n" .
                             "6. **Chronos Calendar** (Operational calendar)\n" .
                             "7. **Owner KPI** (Operational excellence statistics)\n" .
                             "8. **Reports** (Financial analytical reports)\n" .
                             "9. **Team Management** (Team access control configuration)\n" .
                             "10. **Settings** (System settings)\n" .
                             "11. **Security Center** (Encryption security hub)\n" .
                             "12. **Rooterin Guide** (System SOP guide)\n\n" .
                             "You can navigate to any of these features via the sidebar panel.";
                } else {
                    $reply = "Sistem Rooterin-Invoice menyediakan 12 halaman/menu utama untuk role Admin dan Owner:\n" .
                             "1. **Dashboard** (Command Center utama)\n" .
                             "2. **AI Assistant** (Halaman khusus chat ini)\n" .
                             "3. **Clients** (Manajemen data klien)\n" .
                             "4. **Receipts** (Pencatatan kwitansi)\n" .
                             "5. **Invoices** (Manajemen tagihan dan AI Copywriter)\n" .
                             "6. **Chronos Calendar** (Kalender operasional)\n" .
                             "7. **Owner KPI** (Statistik keunggulan operasional)\n" .
                             "8. **Reports** (Laporan analitik keuangan)\n" .
                             "9. **Team Management** (Pengaturan hak akses tim)\n" .
                             "10. **Settings** (Pengaturan sistem)\n" .
                             "11. **Security Center** (Pusat keamanan enkripsi)\n" .
                             "12. **Rooterin Guide** (Panduan SOP sistem)\n\n" .
                             "Anda dapat mengakses seluruh fitur ini langsung melalui menu navigasi di sidebar kiri.";
                }
            } elseif (str_contains($userMsgLower, 'klien') || str_contains($userMsgLower, 'client')) {
                $reply = $locale === 'en'
                    ? "Currently, the system records **{$totalClients} active clients**. You can view and manage client details fully on the Clients page."
                    : "Saat ini sistem mencatat Anda memiliki **{$totalClients} klien aktif**. Anda dapat melihat dan mengelola detail data klien secara lengkap di halaman Klien.";
                $navigateTag = " [NAVIGATE: clients.index]";
            } elseif (str_contains($userMsgLower, 'lunas') || str_contains($userMsgLower, 'paid')) {
                $reply = $locale === 'en'
                    ? "The total amount of paid invoices is **Rp " . number_format($paidTotal, 0, ',', '.') . "** from **{$paidCount} invoices**."
                    : "Total nominal tagihan yang telah lunas (paid) adalah **Rp " . number_format($paidTotal, 0, ',', '.') . "** dari total **{$paidCount} invoice**.";
                $navigateTag = " [NAVIGATE: invoices.index]";
            } elseif (str_contains($userMsgLower, 'menunggak') || str_contains($userMsgLower, 'overdue') || str_contains($userMsgLower, 'jatuh tempo') || str_contains($userMsgLower, 'tunggak')) {
                $replyList = [];
                foreach ($overdueInvoices as $inv) {
                    $replyList[] = "* **Invoice #{$inv->invoice_number}** oleh {$inv->client->nama_client} - Rp " . number_format($inv->total, 0, ',', '.') . " (Jatuh tempo: " . $inv->due_date->format('d M Y') . ")";
                }
                $reply = ($locale === 'en'
                    ? "Here is the list of overdue invoices:\n\n" . (count($replyList) > 0 ? implode("\n", $replyList) : "No overdue invoices at this moment.") . "\n\n**Action Recommendation:** Contact the respective clients immediately or use the AI billing email draft feature to secure payments."
                    : "Berikut adalah daftar invoice yang saat ini berstatus menunggak (overdue):\n\n" . (count($replyList) > 0 ? implode("\n", $replyList) : "Tidak ada invoice menunggak saat ini.") . "\n\n**Rekomendasi Tindakan:** Hubungi klien bersangkutan segera atau gunakan fitur Draf Email Penagihan AI untuk mengamankan pembayaran.");
                $navigateTag = " [NAVIGATE: invoices.index]";
            } elseif (str_contains($userMsgLower, 'buat invoice') || str_contains($userMsgLower, 'tambah invoice') || str_contains($userMsgLower, 'create invoice')) {
                $reply = $locale === 'en'
                    ? "To create a new invoice, you can complete the invoice form on the create invoice page."
                    : "Untuk membuat invoice baru, Anda dapat langsung mengisi form pembuatan invoice pada halaman yang telah disediakan.";
                $navigateTag = " [NAVIGATE: invoices.create]";
            } elseif (str_contains($userMsgLower, 'tambah klien') || str_contains($userMsgLower, 'buat klien') || str_contains($userMsgLower, 'create client')) {
                $reply = $locale === 'en'
                    ? "To add a new client, please open the client management page and fill in the client form."
                    : "Untuk menambahkan klien baru ke dalam sistem, silakan isi form tambah klien pada halaman manajemen klien.";
                $navigateTag = " [NAVIGATE: clients.create]";
            } elseif (str_contains($userMsgLower, 'kuitansi') || str_contains($userMsgLower, 'receipt') || str_contains($userMsgLower, 'tanda terima')) {
                $reply = $locale === 'en'
                    ? "You can view the receipt list or create new receipts from the receipts menu."
                    : "Anda dapat melihat daftar kuitansi atau membuat kuitansi baru melalui menu kuitansi.";
                if (str_contains($userMsgLower, 'buat') || str_contains($userMsgLower, 'tambah') || str_contains($userMsgLower, 'create')) {
                    $navigateTag = " [NAVIGATE: receipts.create]";
                } else {
                    $navigateTag = " [NAVIGATE: receipts.index]";
                }
            } elseif (str_contains($userMsgLower, 'pengaturan') || str_contains($userMsgLower, 'setting')) {
                $reply = $locale === 'en'
                    ? "You can configure company details and general settings on the Settings page."
                    : "Anda dapat mengatur data profil perusahaan dan pengaturan umum sistem melalui halaman Pengaturan.";
                $navigateTag = " [NAVIGATE: settings.index]";
            } elseif (str_contains($userMsgLower, 'profil') || str_contains($userMsgLower, 'akun') || str_contains($userMsgLower, 'profile')) {
                $reply = $locale === 'en'
                    ? "To update your profile information, password, and email, please navigate to your profile page."
                    : "Untuk memperbarui detail akun Anda seperti nama, email, dan password, silakan buka halaman Profil Pengguna.";
                $navigateTag = " [NAVIGATE: profile.edit]";
            } elseif (str_contains($userMsgLower, 'dashboard')) {
                $reply = $locale === 'en'
                    ? "On the Dashboard, you can monitor monthly metrics, outstanding dues, and cash flow insights."
                    : "Di halaman dashboard, Anda dapat memantau grafik penjualan bulanan, total tagihan outstanding, ringkasan aktivitas, serta analisis cashflow secara visual.";
                $navigateTag = " [NAVIGATE: dashboard]";
            } elseif (str_contains($userMsgLower, 'laporan') || str_contains($userMsgLower, 'report')) {
                $reply = $locale === 'en'
                    ? "The Reports page offers visual analysis on revenue, receivables, and historical business KPIs."
                    : "Halaman Laporan menyajikan visualisasi data yang mendalam mengenai pendapatan, piutang, dan statistik bisnis bulanan.";
                $navigateTag = " [NAVIGATE: reports.index]";
            } elseif (str_contains($userMsgLower, 'kalender') || str_contains($userMsgLower, 'chronos')) {
                $reply = $locale === 'en'
                    ? "Chronos Calendar lets you manage invoice timelines and collection deadlines interactively."
                    : "Kalender Chronos mempermudah Anda dalam memantau timeline invoice berdasarkan tanggal jatuh temponya secara interaktif.";
                $navigateTag = " [NAVIGATE: chronos.index]";
            } else {
                $reply = $locale === 'en'
                    ? "Hello! I am your Senior Financial Consultant Virtual Assistant. Current summary:\n* **Active Clients:** {$totalClients}\n* **Paid Invoices:** {$paidCount} (Rp " . number_format($paidTotal, 0, ',', '.') . ")\n* **Pending Invoices:** {$pendingCount} (Rp " . number_format($pendingTotal, 0, ',', '.') . ")\n\nIs there anything specific you would like to analyze today?"
                    : "Halo! Saya adalah Senior Financial Consultant Virtual Anda. Berdasarkan ringkasan terkini:\n* **Klien Aktif:** {$totalClients}\n* **Invoice Lunas:** {$paidCount} (Rp " . number_format($paidTotal, 0, ',', '.') . ")\n* **Invoice Pending:** {$pendingCount} (Rp " . number_format($pendingTotal, 0, ',', '.') . ")\n\nAda hal spesifik mengenai analisis kas atau invoice overdue yang ingin Anda diskusikan?";
            }

            $reply .= $navigateTag;

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
            ];
        } else {
            return [
                'dashboard' => "👉 Buka Dashboard",
                'invoices.index' => "👉 Lihat Daftar Invoice",
                'invoices.create' => "👉 Buat Invoice Baru",
                'clients.index' => "👉 Lihat Daftar Klien",
                'clients.create' => "👉 Tambah Klien Baru",
                'receipts.index' => "👉 Lihat Daftar Kuitansi",
                'receipts.create' => "👉 Buat Kuitansi Baru",
                'settings.index' => "👉 Buka Pengaturan",
                'profile.edit' => "👉 Edit Profil Saya",
                'reports.index' => "👉 Lihat Laporan Keuangan",
                'chronos.index' => "👉 Buka Kalender Billing (Chronos)",
            ];
        }
    }

    public function render()
    {
        return view('livewire.dashboard-chatbot');
    }
}
