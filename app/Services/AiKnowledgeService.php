<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AiKnowledgeService
{
    /**
     * Get the best answer from the database (Function Caller) or knowledge dictionary based on user query.
     *
     * @param string $userMessage
     * @param string $locale
     * @return array
     */
    public function getAnswer(string $userMessage, string $locale = 'id'): array
    {
        $rawQuery = trim($userMessage);
        $normalizedQuery = $this->normalizeQuery($rawQuery);
        
        if (empty($normalizedQuery)) {
            return [
                'text' => $locale === 'en' 
                    ? "Please enter a question." 
                    : "Silakan masukkan pertanyaan Anda.",
                'routeName' => null,
                'similarity' => 0.0,
                'topic' => null
            ];
        }

        // 1. SMALL TALK ENGINE: Handle Greetings
        $greetingResponse = $this->handleGreeting($normalizedQuery, $locale);
        if ($greetingResponse !== null) {
            return [
                'text' => $greetingResponse,
                'routeName' => null,
                'similarity' => 100.0,
                'topic' => 'small_talk'
            ];
        }

        // 2. CONTEXT MEMORY: Accumulation / Addition Query
        $isAdditionQuery = preg_match('/(tambah|tambahkan|jumlahkan|plus|add)/i', $normalizedQuery);
        if ($isAdditionQuery) {
            $lastDataQuery = session()->get('last_data_query');
            if ($lastDataQuery !== null) {
                // Clean formatting to extract raw digits
                $cleanMessage = str_replace(['.', ',', 'rp', 'Rp', ' '], '', $rawQuery);
                if (preg_match('/(\d+)/', $cleanMessage, $numMatches)) {
                    $valueToAdd = (float) $numMatches[1];
                    $newTotal = $lastDataQuery + $valueToAdd;
                    
                    // Update session for chained math queries
                    session()->put('last_data_query', $newTotal);
                    
                    $text = $locale === 'en'
                        ? "### 🧮 Contextual Calculation Results\n\n" .
                          "Based on your request to add to the previous query:\n" .
                          "* **Previous Value:** Rp " . number_format($lastDataQuery, 0, ',', '.') . "\n" .
                          "* **Added Value:** Rp " . number_format($valueToAdd, 0, ',', '.') . "\n" .
                          "* **Final Accumulated Total:** **Rp " . number_format($newTotal, 0, ',', '.') . "**\n\n" .
                          "As your J&J GROUP Senior Financial Consultant, this projects a consolidated total of **Rp " . number_format($newTotal, 0, ',', '.') . "**."
                        : "### 🧮 Hasil Perhitungan Tambahan Konteks\n\n" .
                          "Berdasarkan permintaan Anda untuk menambahkan data sebelumnya:\n" .
                          "* **Nilai Sebelumnya:** Rp " . number_format($lastDataQuery, 0, ',', '.') . "\n" .
                          "* **Nilai Tambahan:** Rp " . number_format($valueToAdd, 0, ',', '.') . "\n" .
                          "* **Total Akhir:** **Rp " . number_format($newTotal, 0, ',', '.') . "**\n\n" .
                          "Sebagai asisten keuangan J&J GROUP, perhitungan akumulasi ini mencerminkan total proyeksi dana baru sebesar **Rp " . number_format($newTotal, 0, ',', '.') . "**.";

                    return [
                        'text' => $text,
                        'routeName' => null,
                        'similarity' => 100.0,
                        'topic' => 'accumulation_calculation'
                    ];
                }
            }
        }

        // 3. STRICT GUARDRAIL CHECK
        if (!$this->isRelatedQuery($normalizedQuery)) {
            return [
                'text' => $locale === 'en'
                    ? "I apologize, but I am solely the J&J GROUP financial assistant. I do not have data for that topic."
                    : "Mohon maaf, saya hanya asisten keuangan J&J GROUP. Saya tidak memiliki data untuk topik tersebut.",
                'routeName' => null,
                'similarity' => 0.0,
                'topic' => 'guardrail_fallback'
            ];
        }

        // 4. INTENT CLASSIFICATION FOR DYNAMIC FINANCIAL DATA QUERIES
        $isProcedureIntent = preg_match('/(cara|prosedur|navigasi|sop|petunjuk|guide|tutorial|bagaimana cara|langkah|buka|pergi|tampilkan|menu|halaman|go to|open|view|show|navigate|how to|step)/i', $normalizedQuery);

        if (!$isProcedureIntent) {
            // Tomorrow's Invoices
            if (preg_match('/(besok|tomorrow)/i', $normalizedQuery)) {
                try {
                    $data = $this->getInvoiceList('tomorrow');
                    if (empty($data) || $data['count'] === 0 || $data['total'] <= 0.0) {
                        return [
                            'text' => $locale === 'en'
                                ? "I apologize, but there are no invoices due tomorrow in our database at the moment. However, you can manage or review all invoices here:"
                                : "Maaf, saat ini saya tidak mendeteksi adanya data invoice yang jatuh tempo besok di database. Namun, Anda dapat melihat daftar tagihan secara manual melalui menu berikut:",
                            'routeName' => 'invoices.index',
                            'similarity' => 100.0,
                            'topic' => 'invoice_besok_fallback'
                        ];
                    }
                    session()->put('last_data_query', (float) $data['total']);
                    $narrative = $this->generateNarrative($data, 'tomorrow', $locale);
                    return [
                        'text' => $narrative,
                        'routeName' => null,
                        'similarity' => 100.0,
                        'topic' => 'invoice_besok'
                    ];
                } catch (\Throwable $e) {
                    return [
                        'text' => $locale === 'en'
                            ? "I apologize, but I cannot retrieve the invoice data directly due to a database limitation. However, you can access the invoices page manually:"
                            : "Maaf, saya tidak dapat memanggil data tagihan langsung dari database saat ini. Anda dapat memeriksa daftar invoice secara manual di menu berikut:",
                        'routeName' => 'invoices.index',
                        'similarity' => 100.0,
                        'topic' => 'invoice_besok_fallback'
                    ];
                }
            }

            // Overdue Invoices
            if (preg_match('/(overdue|menunggak|tunggakan|piutang)/i', $normalizedQuery)) {
                try {
                    $data = $this->getOverdueList();
                    if (empty($data) || $data['count'] === 0 || $data['total'] <= 0.0) {
                        return [
                            'text' => $locale === 'en'
                                ? "I apologize, but there are no overdue invoices recorded in our database at the moment. However, you can check the complete invoices list here:"
                                : "Maaf, saat ini tidak ada data tagihan overdue/menunggak yang terdeteksi di database. Anda dapat meninjau semua status invoice melalui menu berikut:",
                            'routeName' => 'invoices.index',
                            'similarity' => 100.0,
                            'topic' => 'invoice_overdue_fallback'
                        ];
                    }
                    session()->put('last_data_query', (float) $data['total']);
                    $narrative = $this->generateNarrative($data, 'overdue', $locale);
                    return [
                        'text' => $narrative,
                        'routeName' => null,
                        'similarity' => 100.0,
                        'topic' => 'invoice_overdue'
                    ];
                } catch (\Throwable $e) {
                    return [
                        'text' => $locale === 'en'
                            ? "I apologize, but I cannot retrieve the overdue invoice records at the moment. You can view the list manually here:"
                            : "Maaf, saya belum bisa menampilkan data tagihan menunggak secara langsung saat ini. Namun, Anda bisa memantau perkembangannya melalui menu berikut:",
                        'routeName' => 'invoices.index',
                        'similarity' => 100.0,
                        'topic' => 'invoice_overdue_fallback'
                    ];
                }
            }

            // Paid Invoices
            if (preg_match('/(lunas|paid)/i', $normalizedQuery)) {
                try {
                    $data = $this->getInvoiceList('paid');
                    if (empty($data) || $data['count'] === 0 || $data['total'] <= 0.0) {
                        return [
                            'text' => $locale === 'en'
                                ? "I apologize, but there are no paid invoices recorded in our database at the moment. You can check or record new payments through the invoices page:"
                                : "Maaf, saat ini belum ada data tagihan lunas yang tercatat di database J&J GROUP. Anda dapat memperbarui status pembayaran di menu berikut:",
                            'routeName' => 'invoices.index',
                            'similarity' => 100.0,
                            'topic' => 'invoice_lunas_fallback'
                        ];
                    }
                    session()->put('last_data_query', (float) $data['total']);
                    $narrative = $this->generateNarrative($data, 'paid', $locale);
                    return [
                        'text' => $narrative,
                        'routeName' => null,
                        'similarity' => 100.0,
                        'topic' => 'invoice_lunas'
                    ];
                } catch (\Throwable $e) {
                    return [
                        'text' => $locale === 'en'
                            ? "I apologize, but I cannot retrieve the paid invoice records at the moment. Please navigate to the invoices list to review manually:"
                            : "Maaf, saya belum bisa menampilkan data invoice lunas secara langsung saat ini. Namun, Anda dapat mengakses informasinya melalui menu berikut:",
                        'routeName' => 'invoices.index',
                        'similarity' => 100.0,
                        'topic' => 'invoice_lunas_fallback'
                    ];
                }
            }

            // Cash Flow
            if (preg_match('/(arus\s*kas|cash\s*flow|cashflow|likuiditas)/i', $normalizedQuery)) {
                try {
                    $data = $this->getRevenueData('cash_flow');
                    if (empty($data) || ($data['revenue'] <= 0.0 && $data['outstanding'] <= 0.0)) {
                        return [
                            'text' => $locale === 'en'
                                ? "I apologize, but there is insufficient cash flow data to generate an analysis at the moment. Please view our full financial reports here:"
                                : "Maaf, data arus kas saat ini masih kosong atau belum mencukupi untuk dianalisis. Silakan akses detail pelaporan keuangan melalui menu berikut:",
                            'routeName' => 'reports.index',
                            'similarity' => 100.0,
                            'topic' => 'arus_kas_fallback'
                        ];
                    }
                    session()->put('last_data_query', (float) $data['revenue']);
                    $narrative = $this->generateNarrative($data, 'cash_flow', $locale);
                    return [
                        'text' => $narrative,
                        'routeName' => null,
                        'similarity' => 100.0,
                        'topic' => 'arus_kas'
                    ];
                } catch (\Throwable $e) {
                    return [
                        'text' => $locale === 'en'
                            ? "I apologize, but I cannot process the cash flow analysis directly at this time. Please visit the reports page for full details:"
                            : "Maaf, saya belum bisa memproses analisis arus kas secara langsung saat ini. Silakan kunjungi halaman laporan untuk rincian selengkapnya melalui menu berikut:",
                        'routeName' => 'reports.index',
                        'similarity' => 100.0,
                        'topic' => 'arus_kas_fallback'
                    ];
                }
            }

            // Internal Business Units List (Distinct organizational query)
            if (preg_match('/(unit\s*bisnis|business\s*unit|divisi|division)/i', $normalizedQuery)) {
                try {
                    $units = \App\Models\BusinessUnit::all();
                    if ($units->isEmpty()) {
                        return [
                            'text' => $locale === 'en'
                                ? "I apologize, but there are no internal business units registered in our system at the moment. You can manage or add business units here:"
                                : "Maaf, saat ini belum ada unit bisnis internal yang terdaftar di sistem. Anda dapat mengelola unit bisnis melalui menu berikut:",
                            'routeName' => 'business-units.index',
                            'similarity' => 100.0,
                            'topic' => 'unit_bisnis_fallback'
                        ];
                    }

                    $unitLines = [];
                    foreach ($units as $unit) {
                        $unitLines[] = "* **{$unit->name}** (Fee Sharing: " . number_format($unit->fee_percentage, 1, ',', '.') . "%)";
                    }
                    $listStr = implode("\n", $unitLines);

                    $text = $locale === 'en'
                        ? "### 🏢 J&J GROUP Internal Business Units\n\n" .
                          "Our organization consists of the following internal business units/divisions:\n\n" .
                          $listStr . "\n\n" .
                          "**Structural Advisory:** These internal divisions drive our operations. Each unit carries a specific profit-sharing fee percentage. You can manage their configurations or view detailed revenue breakdown using the link below."
                        : "### 🏢 Unit Bisnis Internal J&J GROUP\n\n" .
                          "Organisasi kita terdiri dari divisi/unit bisnis internal berikut:\n\n" .
                          $listStr . "\n\n" .
                          "**Penjelasan Struktur:** Unit bisnis di atas merupakan divisi internal J&J GROUP, bukan klien eksternal. Masing-masing unit memiliki persentase pembagian keuntungan (fee sharing) tersendiri. Anda dapat mengelola unit bisnis ini melalui menu di bawah.";

                    return [
                        'text' => $text,
                        'routeName' => 'business-units.index',
                        'similarity' => 100.0,
                        'topic' => 'unit_bisnis'
                    ];
                } catch (\Throwable $e) {
                    return [
                        'text' => $locale === 'en'
                            ? "I apologize, but I cannot retrieve the business units list at the moment. However, you can access them via the following menu:"
                            : "Maaf, saya belum bisa menampilkan daftar unit bisnis secara langsung saat ini. Namun, Anda bisa mengakses informasinya melalui menu berikut:",
                        'routeName' => 'business-units.index',
                        'similarity' => 100.0,
                        'topic' => 'unit_bisnis_fallback'
                    ];
                }
            }

            // Revenue / Omset / Growth / Trend
            if (preg_match('/(omset|omzet|pendapatan|penghasilan|pemasukan|revenue|income|performa|pertumbuhan|tren|trend|growth)/i', $normalizedQuery)) {
                try {
                    $data = $this->getRevenueData('trend');
                    if (empty($data) || ($data['current'] <= 0.0 && $data['previous'] <= 0.0)) {
                        return [
                            'text' => $locale === 'en'
                                ? "I apologize, but there is no sufficient monthly revenue history to determine growth trends. Please access the reports module to audit our financial trends:"
                                : "Maaf, data pertumbuhan omset saat ini masih kosong atau belum tersedia. Anda dapat melihat detail laporan bulanan melalui menu berikut:",
                            'routeName' => 'reports.index',
                            'similarity' => 100.0,
                            'topic' => 'tren_performa_fallback'
                        ];
                    }
                    session()->put('last_data_query', (float) $data['current']);
                    $narrative = $this->generateNarrative($data, 'trend', $locale);
                    return [
                        'text' => $narrative,
                        'routeName' => null,
                        'similarity' => 100.0,
                        'topic' => 'tren_performa'
                    ];
                } catch (\Throwable $e) {
                    return [
                        'text' => $locale === 'en'
                            ? "I apologize, but I cannot display the revenue trend directly right now. You can analyze the financial performance charts on the page below:"
                            : "Maaf, saya belum bisa menampilkan visualisasi tren pendapatan secara langsung saat ini. Anda dapat menganalisis grafik performa keuangan di halaman berikut:",
                        'routeName' => 'reports.index',
                        'similarity' => 100.0,
                        'topic' => 'tren_performa_fallback'
                    ];
                }
            }

            // Clients List
            if (preg_match('/(klien|client|customer|pelanggan|mitra)/i', $normalizedQuery)) {
                try {
                    $data = $this->getClientList();
                    if (empty($data) || $data['count'] === 0) {
                        return [
                            'text' => $locale === 'en'
                                ? "I apologize, but there are no active clients registered in our database at the moment. You can add or manage clients manually via the following menu:"
                                : "Maaf, saya tidak menemukan adanya data klien aktif di database saat ini. Namun, Anda dapat menambah atau mengelola klien secara manual melalui menu berikut:",
                            'routeName' => 'clients.index',
                            'similarity' => 100.0,
                            'topic' => 'total_klien_fallback'
                        ];
                    }
                    session()->put('last_data_query', (float) $data['count']);
                    $narrative = $this->generateNarrative($data, 'client', $locale);
                    return [
                        'text' => $narrative,
                        'routeName' => 'clients.index', // Include navigation route!
                        'similarity' => 100.0,
                        'topic' => 'total_klien'
                    ];
                } catch (\Throwable $e) {
                    return [
                        'text' => $locale === 'en'
                            ? "I apologize, but I cannot display the client list directly. However, you can access this information via the following menu:"
                            : "Maaf, saya belum bisa menampilkan daftar klien secara langsung. Namun, Anda bisa mengakses informasinya melalui menu berikut:",
                        'routeName' => 'clients.index',
                        'similarity' => 100.0,
                        'topic' => 'total_klien_fallback'
                    ];
                }
            }
        }

        // 5. Fallback to dictionary for Procedures & Navigation
        $dictionary = KnowledgeDictionary::getDictionary();
        $queryWords = array_values(array_filter(explode(' ', $normalizedQuery)));
        $queryWordCount = count($queryWords);

        // Resolve Direct Alias for other terms
        $directAliasResult = $this->resolveDirectAlias($normalizedQuery, $queryWords, $locale, $dictionary);
        if ($directAliasResult !== null) {
            return $directAliasResult;
        }

        $bestMatch = null;
        $bestType  = null;
        $bestKey   = null;
        $maxScore  = 0.0;

        foreach ($dictionary as $type => $items) {
            // Strictly check procedures here
            if ($type !== 'static_procedures') {
                continue;
            }

            foreach ($items as $key => $item) {
                $itemMaxScore = 0.0;
                foreach ($item['keywords'] as $keyword) {
                    $score = $this->calculateSimilarity($normalizedQuery, strtolower($keyword));
                    if ($score > $itemMaxScore) {
                        $itemMaxScore = $score;
                    }
                }

                if ($itemMaxScore > $maxScore) {
                    $maxScore = $itemMaxScore;
                    $bestMatch = $item;
                    $bestType = $type;
                    $bestKey = $key;
                } 
                elseif ($itemMaxScore === $maxScore && $maxScore > 0) {
                    if ($bestMatch === null || $item['priority'] > $bestMatch['priority']) {
                        $bestMatch = $item;
                        $bestType = $type;
                        $bestKey = $key;
                    }
                }
            }
        }

        $effectiveThreshold = ($queryWordCount <= 2) ? 60.0 : 75.0;

        if ($maxScore >= $effectiveThreshold) {
            session()->put('last_topic', $bestKey);

            $text = $this->resolveResponseText($bestMatch, $bestKey, $bestType, $locale, $rawQuery);

            return [
                'text'       => $text,
                'routeName'  => $bestMatch['navigate'] ?? null,
                'similarity' => $maxScore,
                'topic'      => $bestKey
            ];
        }

        // Context Memory follow-up fallback
        $lastTopic = session()->get('last_topic');
        $isFollowUp = preg_match('/(detail|tampilkan|lihat|ulang|berapa|siapa|apa|show|view|again|more|how many|who|what)/i', $normalizedQuery);

        if ($lastTopic && $isFollowUp) {
            $matchedItem = null;
            $matchedType = null;

            foreach ($dictionary as $type => $items) {
                if (isset($items[$lastTopic])) {
                    $matchedItem = $items[$lastTopic];
                    $matchedType = $type;
                    break;
                }
            }

            if ($matchedItem && $matchedType === 'static_procedures') {
                $baseText = $this->resolveResponseText($matchedItem, $lastTopic, $matchedType, $locale, $rawQuery);
                $note = $locale === 'en'
                    ? "\n\n*(Showing info based on your previous question topic)*"
                    : "\n\n*(Menampilkan kembali info berdasarkan konteks topik sebelumnya)*";

                return [
                    'text' => $baseText . $note,
                    'routeName' => $matchedItem['navigate'] ?? null,
                    'similarity' => 100.0,
                    'topic' => $lastTopic
                ];
            }
        }

        $ambiguousResult = $this->handleAmbiguousFinancialWord($normalizedQuery, $locale);
        if ($ambiguousResult !== null) {
            return $ambiguousResult;
        }

        // Default menu
        $helpText = $locale === 'en'
            ? "I'm not sure what you're looking for. Here's what I can help you with:\n\n"
              . "**📊 Financial Data:**\n"
              . "* **\"pendapatan bulan ini\"** → Revenue & growth trend\n"
              . "* **\"arus kas\"** → Cash flow & liquidity analysis\n"
              . "* **\"invoice menunggak\"** → Overdue receivables list\n"
              . "* **\"total invoice lunas\"** → Settled payments total\n"
              . "* **\"total klien\"** → Active client portfolio\n\n"
              . "**📋 Quick Actions:**\n"
              . "* **\"buat invoice\"** → Create a new invoice\n"
              . "* **\"buat klien\"** → Register a new client\n"
              . "* **\"buat kuitansi\"** → Record a receipt\n"
              . "* **\"panduan sistem\"** → View full system guide\n\n"
              . "*Just type any of the above or ask a more specific question!*"
            : "Saya tidak yakin apa yang Anda cari. Berikut yang dapat saya bantu:\n\n"
              . "**📊 Data Keuangan:**\n"
              . "* **\"pendapatan bulan ini\"** → Tren pendapatan & pertumbuhan omset\n"
              . "* **\"arus kas\"** → Analisis posisi kas & likuiditas\n"
              . "* **\"invoice menunggak\"** → Daftar piutang overdue\n"
              . "* **\"total invoice lunas\"** → Total tagihan yang telah dibayar\n"
              . "* **\"total klien\"** → Portofolio klien aktif\n\n"
              . "**📋 Aksi Cepat:**\n"
              . "* **\"buat invoice\"** → Menerbitkan tagihan baru\n"
              . "* **\"buat klien\"** → Mendaftarkan klien baru\n"
              . "* **\"buat kuitansi\"** → Mencatat penerimaan kas\n"
              . "* **\"panduan sistem\"** → Melihat panduan lengkap sistem\n\n"
              . "*Ketik salah satu perintah di atas atau ajukan pertanyaan yang lebih spesifik!*";

        return [
            'text'      => $helpText,
            'routeName' => null,
            'similarity' => 0.0,
            'topic'     => 'help_menu',
        ];
    }

    /**
     * Check if query is a greeting and return small talk response.
     *
     * @param string $query
     * @param string $locale
     * @return string|null
     */
    public function handleGreeting(string $query, string $locale): ?string
    {
        $greetings = [
            'halo', 'hallo', 'hai', 'hi', 'hey', 'pagi', 'siang', 'sore', 'malam', 'kabar', 'assalamualaikum',
            'hello', 'good morning', 'good afternoon', 'good evening', 'how are you'
        ];

        $isGreeting = false;
        foreach ($greetings as $greet) {
            if (preg_match('/\b' . preg_quote($greet, '/') . '\b/i', $query)) {
                $isGreeting = true;
                break;
            }
        }

        if (!$isGreeting) {
            return null;
        }

        if ($locale === 'en') {
            return "Hello! I am your Senior Financial Consultant & Business Analyst for J&J GROUP. " .
                   "I am ready to assist you in auditing invoice pipelines, analyzing MoM revenue growth, tracking cash flow, or guiding you through our operational systems. " .
                   "How can I assist you with your business goals today?";
        } else {
            return "Halo! Saya adalah Senior Financial Consultant & Business Analyst Anda di J&J GROUP. " .
                   "Saya siap membantu Anda memantau alur invoice, menganalisis pertumbuhan omset bulanan, meninjau posisi kas, maupun memandu Anda menavigasi modul sistem operasional kita. " .
                   "Ada yang bisa saya bantu untuk mendukung keputusan bisnis Anda hari ini?";
        }
    }

    /**
     * Check if the query is related to J&J GROUP financial data, business, or procedures.
     *
     * @param string $query
     * @return bool
     */
    protected function isRelatedQuery(string $query): bool
    {
        $domainKeywords = [
            'invoice', 'tagihan', 'piutang', 'overdue', 'tunggakan', 'menunggak', 'lunas', 'paid', 'bayar',
            'omset', 'omzet', 'pendapatan', 'penghasilan', 'pemasukan', 'revenue', 'income', 'profit',
            'klien', 'client', 'customer', 'pelanggan', 'mitra',
            'unit bisnis', 'business unit', 'divisi', 'division',
            'arus kas', 'cashflow', 'cash flow', 'kas', 'likuiditas',
            'performa', 'pertumbuhan', 'tren', 'trend', 'growth', 'perkembangan', 'analisis', 'analisa',
            'kuitansi', 'kwitansi', 'receipt',
            'laporan', 'report',
            'kalender', 'chronos', 'calendar',
            'panduan', 'sop', 'petunjuk', 'guide', 'tutorial', 'menu', 'halaman', 'navigasi', 'buka', 'pergi',
            'buat', 'tambah', 'bikin', 'create', 'register',
            'setting', 'pengaturan', 'konfigurasi',
            'keamanan', 'security', '2fa', 'enkripsi',
            'profil', 'profile', 'password', 'sandi',
            'dashboard', 'beranda', 'kpi', 'team', 'tim',
            'tambah', 'tambahkan', 'jumlah', 'jumlahkan', 'plus', 'add'
        ];

        foreach ($domainKeywords as $keyword) {
            if (str_contains($query, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Fetch active clients.
     *
     * @return array
     */
    public function getClientList(): array
    {
        $clients = \App\Models\Client::where('status', 'aktif')->get();
        return [
            'count' => $clients->count(),
            'clients' => $clients->toArray(),
        ];
    }

    /**
     * Fetch overdue invoices.
     *
     * @return array
     */
    public function getOverdueList(): array
    {
        $overdueInvoices = \App\Models\Invoice::with('client')
            ->whereIn('status', ['sent', 'pending', 'dp'])
            ->where('due_date', '<', Carbon::now())
            ->get();
        return [
            'count' => $overdueInvoices->count(),
            'total' => (float) $overdueInvoices->sum('total'),
            'invoices' => $overdueInvoices->toArray(),
        ];
    }

    /**
     * Fetch invoice summary.
     *
     * @param string $type
     * @return array
     */
    public function getInvoiceList(string $type = 'all'): array
    {
        if ($type === 'paid') {
            $paidInvoices = \App\Models\Invoice::where('status', 'paid')->get();
            return [
                'count' => $paidInvoices->count(),
                'total' => (float) $paidInvoices->sum('total'),
                'type' => 'paid',
            ];
        } elseif ($type === 'tomorrow') {
            $tomorrowStart = Carbon::tomorrow()->startOfDay()->toDateTimeString();
            $tomorrowEnd = Carbon::tomorrow()->endOfDay()->toDateTimeString();
            $invoices = \App\Models\Invoice::with('client')
                ->whereIn('status', ['sent', 'pending', 'dp'])
                ->whereBetween('due_date', [$tomorrowStart, $tomorrowEnd])
                ->get();
            return [
                'count' => $invoices->count(),
                'total' => (float) $invoices->sum('total'),
                'invoices' => $invoices->toArray(),
                'type' => 'tomorrow',
            ];
        } else {
            $totalInvoices = \App\Models\Invoice::count();
            $totalAmount = (float) \App\Models\Invoice::sum('total');
            return [
                'count' => $totalInvoices,
                'total' => $totalAmount,
                'type' => 'all',
            ];
        }
    }

    /**
     * Fetch revenue / growth stats.
     *
     * @param string $type
     * @return array
     */
    public function getRevenueData(string $type = 'trend'): array
    {
        if ($type === 'cash_flow') {
            $stats = app(\App\Services\BusinessUnitReportingService::class)->getSummaryStats();
            return [
                'revenue' => (float) $stats['total_revenue'],
                'outstanding' => (float) $stats['total_outstanding'],
                'pending' => (float) $stats['pending_outstanding'],
                'overdue' => (float) $stats['overdue_outstanding'],
                'rate' => (float) $stats['collection_rate'],
                'type' => 'cash_flow',
            ];
        } else {
            $trend = app(\App\Services\DataAggregatorService::class)->getRevenueTrend();

            // Calculate Gross, Expenses, Net
            $now = Carbon::now();
            $startOfCurrMonth = $now->copy()->startOfMonth()->toDateString();
            $endOfCurrMonth = $now->copy()->endOfMonth()->toDateString();
            
            $currSummary = app(\App\Services\BusinessUnitReportingService::class)->getBusinessUnitsSummary([
                'start_date' => $startOfCurrMonth,
                'end_date' => $endOfCurrMonth
            ]);
            $gross = (float) $currSummary->sum('gross_revenue');
            $expenses = (float) $currSummary->sum('fee_nominal');
            $net = (float) $currSummary->sum('net_revenue');

            return [
                'current' => (float) $trend['current_revenue'],
                'previous' => (float) $trend['previous_revenue'],
                'growth' => (float) $trend['growth_percent'],
                'insight' => $trend['insight'],
                'gross' => $gross,
                'expenses' => $expenses,
                'net' => $net,
                'type' => 'trend',
            ];
        }
    }

    /**
     * Generate narrative response as Senior Financial Consultant.
     *
     * @param array $data
     * @param string $type
     * @param string $locale
     * @return string
     */
    public function generateNarrative(array $data, string $type, string $locale = 'id'): string
    {
        switch ($type) {
            case 'client':
                $count = $data['count'];
                $clientNames = [];
                foreach ($data['clients'] as $client) {
                    $clientNames[] = "* **{$client['nama_client']}**" . (!empty($client['nama_perusahaan']) ? " ({$client['nama_perusahaan']})" : "");
                }
                $listStr = count($clientNames) > 0 ? implode("\n", $clientNames) : ($locale === 'en' ? "- No active clients." : "- Tidak ada klien aktif.");

                return $locale === 'en'
                    ? "[Analisis Data]\n" .
                      "Based on direct database access, J&J GROUP currently has {$count} active clients:\n" .
                      $listStr . "\n\n" .
                      "[Dampak Bisnis]\n" .
                      "This client base represents the core revenue channels. Maintaining high client satisfaction directly impacts retention and billing cycles.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Initiate credit evaluations for all active accounts and establish quarterly contract reviews to secure predictable, long-term invoice generation."
                    : "[Analisis Data]\n" .
                      "Berdasarkan data sistem terkini, J&J GROUP mengelola {$count} klien aktif:\n" .
                      $listStr . "\n\n" .
                      "[Dampak Bisnis]\n" .
                      "Klien aktif merupakan sumber pendapatan utama perusahaan. Kelancaran administrasi klien memengaruhi ketepatan siklus penagihan dan retensi bisnis.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Lakukan evaluasi kredit berkala terhadap seluruh akun aktif dan jadwalkan peninjauan kontrak triwulanan untuk mengamankan stabilitas omset.";

            case 'overdue':
                $count = $data['count'];
                $totalStr = "Rp " . number_format($data['total'], 0, ',', '.');
                $listItems = [];
                foreach ($data['invoices'] as $inv) {
                    $clientName = $inv['client']['nama_client'] ?? 'Klien';
                    $listItems[] = "* **Invoice #{$inv['invoice_number']}** oleh {$clientName} | **Rp " . number_format($inv['total'], 0, ',', '.') . "** (Due: " . Carbon::parse($inv['due_date'])->format('d M Y') . ")";
                }
                $listStr = count($listItems) > 0 ? implode("\n", $listItems) : ($locale === 'en' ? "- No overdue invoices." : "- Tidak ada invoice menunggak.");

                return $locale === 'en'
                    ? "[Analisis Data]\n" .
                      "Database records show {$count} overdue invoices totaling {$totalStr} in outstanding receivables:\n" .
                      $listStr . "\n\n" .
                      "[Dampak Bisnis]\n" .
                      "Uncollected receivables of {$totalStr} lock up operational working capital and directly restrict cash conversion efficiency.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Dispatch formal payment reminders immediately, prioritize collections on the largest outstanding accounts, and suspend current milestones for accounts with significant aging."
                    : "[Analisis Data]\n" .
                      "Data invoice J&J GROUP menunjukkan terdapat {$count} tagihan overdue/menunggak senilai total {$totalStr}:\n" .
                      $listStr . "\n\n" .
                      "[Dampak Bisnis]\n" .
                      "Piutang sebesar {$totalStr} yang tertahan membatasi likuiditas modal kerja operasional dan menghambat perputaran arus kas bersih.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Kirimkan surat pengingat resmi secara terarah, prioritaskan penagihan pada nominal penunggak terbesar, serta tangguhkan sementara proyek berjalan untuk klien dengan tunggakan kritis.";

            case 'paid':
                $count = $data['count'];
                $totalStr = "Rp " . number_format($data['total'], 0, ',', '.');
                return $locale === 'en'
                    ? "[Analisis Data]\n" .
                      "Transaction logs confirm J&J GROUP successfully collected {$totalStr} from {$count} fully settled invoices.\n\n" .
                      "[Dampak Bisnis]\n" .
                      "A high invoice settlement rate maximizes liquidity and strengthens J&J GROUP's operational cash buffer.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Allocate at least 15% of this realized cash directly to operational reserves before initiating any new capital expenditures."
                    : "[Analisis Data]\n" .
                      "Pencatatan transaksi J&J GROUP mengonfirmasi keberhasilan penagihan senilai {$totalStr} dari {$count} invoice yang telah lunas.\n\n" .
                      "[Dampak Bisnis]\n" .
                      "Pelunasan tagihan yang tepat waktu mengoptimalkan likuiditas kas operasional J&J GROUP dan meminimalkan ketergantungan pada pembiayaan eksternal.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Sisihkan minimal 15% dari dana pelunasan ini ke pos cadangan kas sebelum merencanakan pengeluaran modal (capex) baru.";

            case 'tomorrow':
                $count = $data['count'];
                $totalStr = "Rp " . number_format($data['total'], 0, ',', '.');
                $listItems = [];
                foreach ($data['invoices'] as $inv) {
                    $clientName = $inv['client']['nama_client'] ?? 'Klien';
                    $listItems[] = "* **Invoice #{$inv['invoice_number']}** oleh {$clientName} - **Rp " . number_format($inv['total'], 0, ',', '.') . "**";
                }
                $listStr = count($listItems) > 0 ? implode("\n", $listItems) : ($locale === 'en' ? "- No invoices due tomorrow." : "- Tidak ada invoice jatuh tempo besok.");

                return $locale === 'en'
                    ? "[Analisis Data]\n" .
                      "Live system records indicate {$count} invoices totaling {$totalStr} are due tomorrow:\n" .
                      $listStr . "\n\n" .
                      "[Dampak Bisnis]\n" .
                      "Prompt payment on these invoices tomorrow is vital to sustaining J&J GROUP's weekly cash buffer.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Instruct the billing team to conduct a polite courtesy follow-up with the respective clients today to ensure swift funds realization."
                    : "[Analisis Data]\n" .
                      "Berdasarkan data riil J&J GROUP, terdapat {$count} invoice senilai {$totalStr} yang jatuh tempo besok:\n" .
                      $listStr . "\n\n" .
                      "[Dampak Bisnis]\n" .
                      "Pencairan dana tepat waktu besok sangat krusial untuk menjaga ketersediaan modal kerja mingguan J&J GROUP.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Tugaskan tim penagihan untuk melakukan konfirmasi tagihan secara profesional hari ini guna menjamin kelancaran pelunasan besok.";

            case 'cash_flow':
                $revenueStr = "Rp " . number_format($data['revenue'], 0, ',', '.');
                $outstandingStr = "Rp " . number_format($data['outstanding'], 0, ',', '.');
                $pendingStr = "Rp " . number_format($data['pending'], 0, ',', '.');
                $overdueStr = "Rp " . number_format($data['overdue'], 0, ',', '.');
                $rateStr = number_format($data['rate'], 1, ',', '.');

                return $locale === 'en'
                    ? "[Analisis Data]\n" .
                      "J&J GROUP's consolidated cash flow status:\n" .
                      "- Realized Inflow (Paid): {$revenueStr}\n" .
                      "- Total Outstanding Receivables: {$outstandingStr}\n" .
                      "  - Pending / Active: {$pendingStr}\n" .
                      "  - Overdue / Delayed: {$overdueStr}\n" .
                      "- Collection Efficiency Rate: {$rateStr}%\n\n" .
                      "[Dampak Bisnis]\n" .
                      "The collection efficiency rate of {$rateStr}% demonstrates strong operational execution, but the {$overdueStr} in overdue receivables introduces cash constraint risks.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Review overdue accounts weekly, implement automated payment reminders, and optimize credit terms for repeat clients."
                    : "[Analisis Data]\n" .
                      "Status konsolidasi arus kas J&J GROUP saat ini:\n" .
                      "- Kas Masuk Terealisasi (Lunas): {$revenueStr}\n" .
                      "- Total Piutang Belum Dibayar: {$outstandingStr}\n" .
                      "  - Dalam Termin (Pending): {$pendingStr}\n" .
                      "  - Terlambat (Overdue): {$overdueStr}\n" .
                      "- Rasio Kolektibilitas Tagihan: {$rateStr}%\n\n" .
                      "[Dampak Bisnis]\n" .
                      "Rasio kolektibilitas sebesar {$rateStr}% menunjukkan performa penagihan yang baik, namun piutang overdue senilai {$overdueStr} berpotensi menekan arus kas jangka pendek.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      "Lakukan evaluasi piutang mingguan secara ketat, aktifkan pengingat tagihan otomatis, serta perketat batas kredit untuk klien baru.";

            case 'trend':
                $currStr = "Rp " . number_format($data['current'], 0, ',', '.');
                $prevStr = "Rp " . number_format($data['previous'], 0, ',', '.');
                $growthStr = number_format($data['growth'], 1, ',', '.');
                $insight = $data['insight'];
                
                $grossStr = "Rp " . number_format($data['gross'], 0, ',', '.');
                $expensesStr = "Rp " . number_format($data['expenses'], 0, ',', '.');
                $netStr = "Rp " . number_format($data['net'], 0, ',', '.');

                return $locale === 'en'
                    ? "[Analisis Data]\n" .
                      "Month-over-Month (MoM) revenue trend indicators:\n" .
                      "- Previous Month Revenue: {$prevStr}\n" .
                      "- Current Month Revenue: {$currStr}\n" .
                      "- Revenue Growth Rate: {$growthStr}%\n" .
                      "- Omset Kotor (Gross Revenue): {$grossStr}\n" .
                      "- Beban Operasional (Expenses): {$expensesStr}\n" .
                      "- Omset Bersih (Net Revenue): {$netStr}\n\n" .
                      "[Dampak Bisnis]\n" .
                      "J&J GROUP generated a gross revenue of {$grossStr}. Under our current profit-sharing structure, division fee allocations and expenses of {$expensesStr} leave a net revenue of {$netStr}. {$insight}.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      ($data['growth'] > 0
                          ? "Reinvest a portion of these net gains into our highest-performing business units to sustain growth momentum."
                          : "Review pricing models and implement cost-control measures across underperforming business units immediately.")
                    : "[Analisis Data]\n" .
                      "Indikator tren performa keuangan Month-over-Month (MoM):\n" .
                      "- Pendapatan Bulan Lalu: {$prevStr}\n" .
                      "- Pendapatan Bulan Ini: {$currStr}\n" .
                      "- Tingkat Pertumbuhan: {$growthStr}%\n" .
                      "- Omset Kotor (Gross Revenue): {$grossStr}\n" .
                      "- Beban Operasional (Expenses): {$expensesStr}\n" .
                      "- Omset Bersih (Net Revenue): {$netStr}\n\n" .
                      "[Dampak Bisnis]\n" .
                      "J&J GROUP membukukan omset kotor {$grossStr}. Setelah dikurangi beban operasional dan alokasi fee divisi sebesar {$expensesStr}, pendapatan bersih bernilai {$netStr}. {$insight}.\n\n" .
                      "[Rekomendasi Aksi]\n" .
                      ($data['growth'] > 0
                          ? "Alokasikan sebagian keuntungan bersih untuk pengembangan unit bisnis dengan tingkat pengembalian tertinggi guna menjaga momentum pertumbuhan."
                          : "Lakukan peninjauan struktur biaya operasional dan restrukturisasi penawaran unit bisnis yang kurang produktif secara intensif.");

            default:
                return '';
        }
    }

    /**
     * Direct-alias lookup: maps unambiguous single financial words to a specific
     * dictionary topic, completely bypassing fuzzy scoring.
     */
    protected function resolveDirectAlias(
        string $normalizedQuery,
        array  $queryWords,
        string $locale,
        array  $dictionary
    ): ?array {
        $aliases = [
            'piutang'      => 'invoice_overdue',
            'overdue'      => 'invoice_overdue',
            'menunggak'    => 'invoice_overdue',
            'tunggakan'    => 'invoice_overdue',
            'lunas'        => 'invoice_lunas',
            'paid'         => 'invoice_lunas',
            'klien'        => 'client_portfolio',
            'client'       => 'client_portfolio',
            'clients'      => 'client_portfolio',
            'mitra'        => 'client_portfolio',
            'customer'     => 'client_portfolio',
            'pelanggan'    => 'client_portfolio',
            'unit bisnis'  => 'unit_bisnis',
            'business unit'=> 'unit_bisnis',
            'divisi'       => 'unit_bisnis',
            'division'     => 'unit_bisnis',
            'pendapatan'   => 'revenue_trend',
            'omset'        => 'revenue_trend',
            'omzet'        => 'revenue_trend',
            'penghasilan'  => 'revenue_trend',
            'pemasukan'    => 'revenue_trend',
            'kuitansi'     => 'kuitansi_list',
            'kwitansi'     => 'kuitansi_list',
            'receipt'      => 'kuitansi_list',
            'receipts'     => 'kuitansi_list',
            'performa'     => 'tren_performa',
            'pertumbuhan'  => 'tren_performa',
            'tren'         => 'tren_performa',
            'trend'        => 'tren_performa',
            'growth'       => 'tren_performa',
            'cashflow'     => 'arus_kas',
            'kas'          => 'arus_kas',
            'likuiditas'   => 'arus_kas',
            'laporan'      => 'laporan_keuangan',
            'report'       => 'laporan_keuangan',
            'reports'      => 'laporan_keuangan',
            'kalender'     => 'kalender_chronos',
            'chronos'      => 'kalender_chronos',
            'calendar'     => 'kalender_chronos',
            'owner kpi'    => 'owner_kpi',
            'kpi owner'    => 'owner_kpi',
            'manajemen tim'=> 'manajemen_tim',
            'kelola tim'   => 'manajemen_tim',
        ];

        foreach ($aliases as $alias => $topicKey) {
            $hasAlias = false;
            if ($normalizedQuery === $alias) {
                $hasAlias = true;
            } elseif (str_contains($alias, ' ')) {
                if (str_contains($normalizedQuery, $alias)) {
                    $hasAlias = true;
                }
            } else {
                if (in_array($alias, $queryWords, true)) {
                    $hasAlias = true;
                }
            }

            if (!$hasAlias) {
                continue;
            }

            // Direct mapping of data aliases to the actual database functions
            if (in_array($topicKey, ['invoice_overdue', 'invoice_lunas', 'client_portfolio', 'revenue_trend', 'tren_performa', 'arus_kas', 'unit_bisnis'])) {
                $routeName = null;
                if ($topicKey === 'invoice_overdue') {
                    try {
                        $data = $this->getOverdueList();
                        if (empty($data) || $data['count'] === 0 || $data['total'] <= 0.0) {
                            $text = $locale === 'en'
                                ? "I apologize, but there are no overdue invoices recorded in our database at the moment. However, you can check the complete invoices list here:"
                                : "Maaf, saat ini tidak ada data tagihan overdue/menunggak yang terdeteksi di database. Anda dapat meninjau semua status invoice melalui menu berikut:";
                            $routeName = 'invoices.index';
                        } else {
                            session()->put('last_data_query', (float) $data['total']);
                            $text = $this->generateNarrative($data, 'overdue', $locale);
                        }
                    } catch (\Throwable $e) {
                        $text = $locale === 'en'
                            ? "I apologize, but I cannot retrieve the overdue invoice records at the moment. You can view the list manually here:"
                            : "Maaf, saya belum bisa menampilkan data tagihan menunggak secara langsung saat ini. Namun, Anda bisa memantau perkembangannya melalui menu berikut:";
                        $routeName = 'invoices.index';
                    }
                } elseif ($topicKey === 'invoice_lunas') {
                    try {
                        $data = $this->getInvoiceList('paid');
                        if (empty($data) || $data['count'] === 0 || $data['total'] <= 0.0) {
                            $text = $locale === 'en'
                                ? "I apologize, but there are no paid invoices recorded in our database at the moment. You can check or record new payments through the invoices page:"
                                : "Maaf, saat ini belum ada data tagihan lunas yang tercatat di database J&J GROUP. Anda dapat memperbarui status pembayaran di menu berikut:";
                            $routeName = 'invoices.index';
                        } else {
                            session()->put('last_data_query', (float) $data['total']);
                            $text = $this->generateNarrative($data, 'paid', $locale);
                        }
                    } catch (\Throwable $e) {
                        $text = $locale === 'en'
                            ? "I apologize, but I cannot retrieve the paid invoice records at the moment. Please navigate to the invoices list to review manually:"
                            : "Maaf, saya belum bisa menampilkan data invoice lunas secara langsung saat ini. Namun, Anda dapat mengakses informasinya melalui menu berikut:";
                        $routeName = 'invoices.index';
                    }
                } elseif ($topicKey === 'client_portfolio') {
                    try {
                        $data = $this->getClientList();
                        if (empty($data) || $data['count'] === 0) {
                            $text = $locale === 'en'
                                ? "I apologize, but there are no active clients registered in our database at the moment. You can add or manage clients manually via the following menu:"
                                : "Maaf, saya tidak menemukan adanya data klien aktif di database saat ini. Namun, Anda dapat menambah atau mengelola klien secara manual melalui menu berikut:";
                            $routeName = 'clients.index';
                        } else {
                            session()->put('last_data_query', (float) $data['count']);
                            $text = $this->generateNarrative($data, 'client', $locale);
                            $routeName = 'clients.index'; // Always include navigation button
                        }
                    } catch (\Throwable $e) {
                        $text = $locale === 'en'
                            ? "I apologize, but I cannot display the client list directly. However, you can access this information via the following menu:"
                            : "Maaf, saya belum bisa menampilkan daftar klien secara langsung. Namun, Anda bisa mengakses informasinya melalui menu berikut:";
                        $routeName = 'clients.index';
                    }
                } elseif ($topicKey === 'unit_bisnis') {
                    try {
                        $units = \App\Models\BusinessUnit::all();
                        if ($units->isEmpty()) {
                            $text = $locale === 'en'
                                ? "I apologize, but there are no internal business units registered in our system at the moment. You can manage or add business units here:"
                                : "Maaf, saat ini belum ada unit bisnis internal yang terdaftar di sistem. Anda dapat mengelola unit bisnis melalui menu berikut:";
                            $routeName = 'business-units.index';
                        } else {
                            $unitLines = [];
                            foreach ($units as $unit) {
                                $unitLines[] = "* **{$unit->name}** (Fee Sharing: " . number_format($unit->fee_percentage, 1, ',', '.') . "%)";
                            }
                            $listStr = implode("\n", $unitLines);
                            $text = $locale === 'en'
                                ? "### 🏢 J&J GROUP Internal Business Units\n\n" .
                                  "Our organization consists of the following internal business units/divisions:\n\n" .
                                  $listStr . "\n\n" .
                                  "**Structural Advisory:** These internal divisions drive our operations. Each unit carries a specific profit-sharing fee percentage. You can manage their configurations or view detailed revenue breakdown using the link below."
                                : "### 🏢 Unit Bisnis Internal J&J GROUP\n\n" .
                                  "Organisasi kita terdiri dari divisi/unit bisnis internal berikut:\n\n" .
                                  $listStr . "\n\n" .
                                  "**Penjelasan Struktur:** Unit bisnis di atas merupakan divisi internal J&J GROUP, bukan klien eksternal. Masing-masing unit memiliki persentase pembagian keuntungan (fee sharing) tersendiri. Anda dapat mengelola unit bisnis ini melalui menu di bawah:";
                            $routeName = 'business-units.index';
                        }
                    } catch (\Throwable $e) {
                        $text = $locale === 'en'
                            ? "I apologize, but I cannot retrieve the business units list at the moment. However, you can access them via the following menu:"
                            : "Maaf, saya belum bisa menampilkan daftar unit bisnis secara langsung saat ini. Namun, Anda bisa mengakses informasinya melalui menu berikut:";
                        $routeName = 'business-units.index';
                    }
                } elseif ($topicKey === 'revenue_trend' || $topicKey === 'tren_performa') {
                    try {
                        $data = $this->getRevenueData('trend');
                        if (empty($data) || ($data['current'] <= 0.0 && $data['previous'] <= 0.0)) {
                            $text = $locale === 'en'
                                ? "I apologize, but there is no sufficient monthly revenue history to determine growth trends. Please access the reports module to audit our financial trends:"
                                : "Maaf, data pertumbuhan omset saat ini masih kosong atau belum tersedia. Anda dapat melihat detail laporan bulanan melalui menu berikut:";
                            $routeName = 'reports.index';
                        } else {
                            session()->put('last_data_query', (float) $data['current']);
                            $text = $this->generateNarrative($data, 'trend', $locale);
                        }
                    } catch (\Throwable $e) {
                        $text = $locale === 'en'
                            ? "I apologize, but I cannot display the revenue trend directly right now. You can analyze the financial performance charts on the page below:"
                            : "Maaf, saya belum bisa menampilkan visualisasi tren pendapatan secara langsung saat ini. Anda dapat menganalisis grafik performa keuangan di halaman berikut:";
                        $routeName = 'reports.index';
                    }
                } elseif ($topicKey === 'arus_kas') {
                    try {
                        $data = $this->getRevenueData('cash_flow');
                        if (empty($data) || ($data['revenue'] <= 0.0 && $data['outstanding'] <= 0.0)) {
                            $text = $locale === 'en'
                                ? "I apologize, but there is insufficient cash flow data to generate an analysis at the moment. Please view our full financial reports here:"
                                : "Maaf, data arus kas saat ini masih kosong atau belum mencukupi untuk dianalisis. Silakan akses detail pelaporan keuangan melalui menu berikut:";
                            $routeName = 'reports.index';
                        } else {
                            session()->put('last_data_query', (float) $data['revenue']);
                            $text = $this->generateNarrative($data, 'cash_flow', $locale);
                        }
                    } catch (\Throwable $e) {
                        $text = $locale === 'en'
                            ? "I apologize, but I cannot process the cash flow analysis directly at this time. Please visit the reports page for full details:"
                            : "Maaf, saya belum bisa memproses analisis arus kas secara langsung saat ini. Silakan kunjungi halaman laporan untuk rincian selengkapnya melalui menu berikut:";
                        $routeName = 'reports.index';
                    }
                }

                return [
                    'text'       => $text,
                    'routeName'  => $routeName,
                    'similarity' => 100.0,
                    'topic'      => $topicKey
                ];
            }

            // Fallback for static procedures / indexes
            foreach ($dictionary as $type => $items) {
                if (!isset($items[$topicKey])) {
                    continue;
                }

                $item = $items[$topicKey];
                session()->put('last_topic', $topicKey);
                $text = $this->resolveResponseText($item, $topicKey, $type, $locale, $normalizedQuery);

                return [
                    'text'       => $text,
                    'routeName'  => $item['navigate'] ?? null,
                    'similarity' => 100.0,
                    'topic'      => $topicKey
                ];
            }
        }

        return null;
    }

    /**
     * Ambiguous financial word handler: fires when the user types a recognised
     * financial term that maps to more than one possible action.
     */
    protected function handleAmbiguousFinancialWord(string $query, string $locale): ?array
    {
        $menus = [
            'invoice' => [
                'id' => "Saya mendeteksi Anda bertanya tentang **invoice**. Apa yang ingin Anda lakukan?\n\n"
                    . "* Ketik **\"invoice menunggak\"** → melihat daftar tagihan overdue\n"
                    . "* Ketik **\"total invoice lunas\"** → melihat total tagihan yang sudah dibayar\n"
                    . "* Ketik **\"cara buat invoice\"** → panduan membuat invoice baru\n"
                    . "* Ketik **\"analisis arus kas\"** → ringkasan posisi keuangan keseluruhan",
                'en' => "I detected you're asking about **invoices**. What would you like to do?\n\n"
                    . "* Type **\"overdue invoices\"** → view unpaid receivables\n"
                    . "* Type **\"paid invoices\"** → view total settled payments\n"
                    . "* Type **\"create invoice\"** → step-by-step guide to creating a new invoice\n"
                    . "* Type **\"cash flow analysis\"** → full financial position overview",
            ],
            'tagihan' => [
                'id' => "Saya mendeteksi Anda bertanya tentang **tagihan**. Silakan perjelas:\n\n"
                    . "* Ketik **\"invoice menunggak\"** → melihat tagihan yang belum dibayar\n"
                    . "* Ketik **\"total invoice lunas\"** → melihat total tagihan lunas\n"
                    . "* Ketik **\"cara buat invoice\"** → panduan membuat tagihan baru",
                'en' => "I detected you're asking about **billing**. Please clarify:\n\n"
                    . "* Type **\"overdue invoices\"** → unpaid billing\n"
                    . "* Type **\"paid invoices\"** → settled payments\n"
                    . "* Type **\"create invoice\"** → new invoice creation guide",
            ],
        ];

        foreach ($menus as $word => $responses) {
            $queryWords = array_filter(explode(' ', $query));
            if ($query === $word || in_array($word, $queryWords, true)) {
                return [
                    'text'       => $locale === 'en' ? $responses['en'] : $responses['id'],
                    'routeName'  => null,
                    'similarity' => 80.0,
                    'topic'      => 'ambiguous_' . $word
                ];
            }
        }

        return null;
    }

    /**
     * Normalize the user query by mapping common synonyms.
     */
    protected function normalizeQuery(string $query): string
    {
        $query = strtolower(trim($query));
        $synonyms = KnowledgeDictionary::getSynonyms();
        uksort($synonyms, fn($a, $b) => strlen($b) - strlen($a));

        foreach ($synonyms as $search => $replace) {
            $query = preg_replace('/\b' . preg_quote($search, '/') . '\b/u', $replace, $query);
        }

        return $query;
    }

    /**
     * Calculate similarity percentage between query and keyword.
     */
    protected function calculateSimilarity(string $query, string $keyword): float
    {
        if ($query === $keyword) {
            return 100.0;
        }

        if (str_contains($query, $keyword)) {
            $queryLen = strlen($query);
            $keywordLen = strlen($keyword);
            return 85.0 + ($keywordLen / $queryLen * 15.0);
        }

        $queryWords = explode(' ', $query);
        $keywordWords = explode(' ', $keyword);
        
        $matchedWordsCount = 0;
        $totalWordSimilarity = 0.0;

        foreach ($keywordWords as $kWord) {
            $bestWordSim = 0.0;
            foreach ($queryWords as $qWord) {
                if (empty($qWord) || empty($kWord)) {
                    continue;
                }
                
                $lev = levenshtein($qWord, $kWord);
                $kLen = strlen($kWord);
                $qLen = strlen($qWord);
                $maxLen = max($kLen, $qLen);
                
                $simPercent = $maxLen > 0 ? (1 - ($lev / $maxLen)) * 100.0 : 0.0;

                if ($kLen <= 3 || $qLen <= 3) {
                    if ($lev > 1) {
                        $simPercent = 0.0;
                    }
                } else {
                    if ($lev > 2) {
                        $simPercent = 0.0;
                    }
                }

                if ($simPercent > $bestWordSim) {
                    $bestWordSim = $simPercent;
                }
            }

            if ($bestWordSim >= 60.0) {
                $matchedWordsCount++;
                $totalWordSimilarity += $bestWordSim;
            }
        }

        $wordPercent = 0.0;
        if (count($keywordWords) > 0) {
            $matchRatio = $matchedWordsCount / count($keywordWords);
            $avgSimilarity = $matchedWordsCount > 0 ? ($totalWordSimilarity / $matchedWordsCount) : 0.0;
            $wordPercent = $avgSimilarity * $matchRatio;
        }

        $totalLev = levenshtein($query, $keyword);
        $totalMaxLen = max(strlen($query), strlen($keyword));
        $fullPercent = $totalMaxLen > 0 ? (1 - ($totalLev / $totalMaxLen)) * 100.0 : 0.0;

        return max($fullPercent, $wordPercent);
    }

    /**
     * Resolve the response text based on item type and user intent.
     */
    protected function resolveResponseText(array $item, string $topic, string $type, string $locale, string $userQuery): string
    {
        if ($type === 'static_procedures') {
            return $locale === 'en'
                ? ($item['response_en'] ?? $item['response_id'])
                : ($item['response_id'] ?? $item['response_en']);
        }

        return '';
    }
}
