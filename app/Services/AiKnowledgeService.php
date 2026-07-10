<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AiKnowledgeService
{
    /**
     * Get the best answer from the knowledge dictionary based on user query.
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

        // 1. CONTEXT MEMORY: Accumulation / Addition Query
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

        $dictionary = KnowledgeDictionary::getDictionary();

        // 2. DIRECT ALIAS: Route unambiguous single financial words instantly,
        //    bypassing fuzzy matching entirely so the user never has to type a full phrase.
        $queryWords  = array_values(array_filter(explode(' ', $normalizedQuery)));
        $queryWordCount = count($queryWords);

        $directAliasResult = $this->resolveDirectAlias($normalizedQuery, $queryWords, $locale, $dictionary);
        if ($directAliasResult !== null) {
            return $directAliasResult;
        }

        $bestMatch = null;
        $bestType  = null;
        $bestKey   = null;
        $maxScore  = 0.0;

        // Determine Allowed Dictionary Sections Based on Query Intent (Strict separation)
        $isProcedureIntent = preg_match('/(cara|prosedur|navigasi|sop|petunjuk|guide|tutorial|bagaimana cara|langkah|buka|pergi|tampilkan|menu|halaman|go to|open|view|show|navigate|how to|step)/i', $normalizedQuery);
        $isDataIntent      = preg_match('/(total|berapa|performa|tren|trend|piutang|jumlah|selisih|hitung|prediksi|analysis|analisis|analisa|kinerja|pertumbuhan|growth|arus kas|cash|besok|tomorrow|outstanding|paid|lunas|menunggak|overdue)/i', $normalizedQuery);

        $allowedTypes = ['static_procedures', 'dynamic_data_triggers'];
        if ($isProcedureIntent && !$isDataIntent) {
            $allowedTypes = ['static_procedures'];
        } elseif ($isDataIntent && !$isProcedureIntent) {
            $allowedTypes = ['dynamic_data_triggers'];
        }

        foreach ($dictionary as $type => $items) {
            // STRICT INTENT SEPARATION: Skip dictionary type if it is not in the allowed types
            if (!in_array($type, $allowedTypes)) {
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

        // Adaptive threshold: relax for short queries (≤ 2 words) so single-word
        // financial terms are not rejected due to multi-word keyword mismatch.
        $effectiveThreshold = ($queryWordCount <= 2) ? 60.0 : 75.0;

        if ($maxScore >= $effectiveThreshold) {
            session()->put('last_topic', $bestKey);

            $text = $this->resolveResponseText($bestMatch, $bestKey, $bestType, $locale, $rawQuery);

            // Auto-Navigation Rule (Strictly restricted to specific location queries)
            $isSpecificNavigationQuery = preg_match('/(buka|pergi|navigasi|lokasi|go to|open|navigate|location)/i', $normalizedQuery);
            $routeName = null;
            if ($isSpecificNavigationQuery && !empty($bestMatch['navigate'])) {
                $routeName = $bestMatch['navigate'];
            }

            return [
                'text'       => $text,
                'routeName'  => $routeName,
                'similarity' => $maxScore,
                'topic'      => $bestKey
            ];
        }

        // Fallback checks for Context Memory
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

            if ($matchedItem) {
                // Verify follow-up matches allowed intent types
                $isAllowed = true;
                if ($isProcedureIntent && !$isDataIntent && $matchedType !== 'static_procedures') {
                    $isAllowed = false;
                } elseif ($isDataIntent && !$isProcedureIntent && $matchedType !== 'dynamic_data_triggers') {
                    $isAllowed = false;
                }

                if ($isAllowed) {
                    $baseText = $this->resolveResponseText($matchedItem, $lastTopic, $matchedType, $locale, $rawQuery);
                    $note = $locale === 'en'
                        ? "\n\n*(Showing info based on your previous question topic)*"
                        : "\n\n*(Menampilkan kembali info berdasarkan konteks topik sebelumnya)*";

                    $isSpecificNavigationQuery = preg_match('/(buka|pergi|navigasi|lokasi|go to|open|navigate|location)/i', $normalizedQuery);
                    $routeName = null;
                    if ($isSpecificNavigationQuery && !empty($matchedItem['navigate'])) {
                        $routeName = $matchedItem['navigate'];
                    }

                    return [
                        'text' => $baseText . $note,
                        'routeName' => $routeName,
                        'similarity' => 100.0,
                        'topic' => $lastTopic
                    ];
                }
            }
        }

        // AMBIGUOUS FINANCIAL WORD: offer a helpful clarifying menu instead of
        // a hard rejection when the user types a known-financial but ambiguous word.
        $ambiguousResult = $this->handleAmbiguousFinancialWord($normalizedQuery, $locale);
        if ($ambiguousResult !== null) {
            return $ambiguousResult;
        }

        // STRICT GUARDRAIL: Out-of-domain query – hard rejection.
        return [
            'text'       => $locale === 'en'
                ? "Sorry, that topic was not found in my operational database. I am only the J&J GROUP financial assistant."
                : "Maaf, topik tersebut tidak ditemukan dalam basis data operasional saya. Saya hanya asisten keuangan J&J GROUP.",
            'routeName'  => null,
            'similarity' => $maxScore,
            'topic'      => null
        ];
    }

    // -------------------------------------------------------------------------
    // ALIAS & DISAMBIGUATION HELPERS
    // -------------------------------------------------------------------------

    /**
     * Direct-alias lookup: maps unambiguous single financial words to a specific
     * dictionary topic, completely bypassing fuzzy scoring.
     *
     * The alias table only lists words that have ONE clear meaning in this domain.
     * Ambiguous words (invoice, tagihan) are handled by handleAmbiguousFinancialWord().
     */
    protected function resolveDirectAlias(
        string $normalizedQuery,
        array  $queryWords,
        string $locale,
        array  $dictionary
    ): ?array {
        // Map: alias word => dictionary topic key (unambiguous only)
        $aliases = [
            'piutang'      => 'invoice_overdue',
            'overdue'      => 'invoice_overdue',
            'menunggak'    => 'invoice_overdue',
            'tunggakan'    => 'invoice_overdue',
            'lunas'        => 'invoice_lunas',
            'paid'         => 'invoice_lunas',
            'klien'        => 'total_klien',
            'client'       => 'total_klien',
            'clients'      => 'total_klien',
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
            'kalender'     => 'kalender_billing',
            'chronos'      => 'kalender_billing',
            'calendar'     => 'kalender_billing',
        ];

        // Check if any word in the query exactly matches an alias
        foreach ($aliases as $alias => $topicKey) {
            if (!in_array($alias, $queryWords, true) && $normalizedQuery !== $alias) {
                continue;
            }

            // Locate the item in the dictionary
            foreach ($dictionary as $type => $items) {
                if (!isset($items[$topicKey])) {
                    continue;
                }

                $item = $items[$topicKey];
                session()->put('last_topic', $topicKey);

                $text = $this->resolveResponseText($item, $topicKey, $type, $locale, $normalizedQuery);

                return [
                    'text'       => $text,
                    'routeName'  => null,
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
     * Returns a friendly clarifying menu instead of a hard rejection.
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
            // Only trigger if the query IS or CONTAINS the ambiguous word as a standalone token
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
     *
     * @param string $query
     * @return string
     */
    protected function normalizeQuery(string $query): string
    {
        $query = strtolower(trim($query));
        
        $synonyms = [
            'tagihan' => 'invoice',
            'kwitansi' => 'kuitansi',
            'receipt' => 'kuitansi',
            'customer' => 'klien',
            'pelanggan' => 'klien',
            'mitra' => 'klien',
            'cash flow' => 'arus kas',
            'cashflow' => 'arus kas',
            'aliran kas' => 'arus kas',
            'keuangan' => 'laporan',
            'analisa' => 'analisis',
            'kinerja' => 'performa',
            'bikin' => 'buat',
            'tambah' => 'buat',
            'setting' => 'pengaturan',
            'config' => 'pengaturan',
            'setup' => 'pengaturan',
        ];

        foreach ($synonyms as $search => $replace) {
            $query = preg_replace('/\b' . preg_quote($search, '/') . '\b/i', $replace, $query);
        }

        return $query;
    }

    /**
     * Calculate similarity percentage between query and keyword.
     *
     * @param string $query
     * @param string $keyword
     * @return float
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

                // Thresholds based on word length to avoid false positives:
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
     *
     * @param array $item
     * @param string $topic
     * @param string $type
     * @param string $locale
     * @param string $userQuery
     * @return string
     */
    protected function resolveResponseText(array $item, string $topic, string $type, string $locale, string $userQuery): string
    {
        $normalizedQuery = strtolower($userQuery);

        $isProcedureIntent = preg_match('/(cara|sop|panduan|petunjuk|langkah|tutorial|bagaimana cara|how to|step|guide|bikin|buat)/i', $normalizedQuery);
        $isNavigationIntent = preg_match('/(buka|pergi|tampilkan|navigasi|menu|halaman|go to|open|view|show|navigate)/i', $normalizedQuery);

        // FORCE DATA: dynamic_data_triggers ALWAYS attempt a live narrative query first.
        // This is the primary integration gate — if data exists, it is always shown.
        if ($type === 'dynamic_data_triggers' && !$isProcedureIntent) {
            $analysisText = $this->getNarrativeAnalysis($topic, $locale);
            if ($analysisText) {
                return $analysisText;
            }
            // Fall through to executeDynamicQuery if no narrative case exists for this topic
            return $this->executeDynamicQuery($item['query_type'], $locale);
        }

        // Navigation (only for explicit navigation queries on procedure items)
        if ($isNavigationIntent && !empty($item['navigate'])) {
            return $locale === 'en'
                ? "Understood. I will direct you to the requested section. Please select the navigation option below."
                : "Tentu, saya akan membantu Anda menuju ke menu yang relevan. Silakan pilih tombol navigasi di bawah ini.";
        }

        // Procedures
        if ($type === 'static_procedures') {
            return $locale === 'en'
                ? ($item['response_en'] ?? $item['response_id'])
                : ($item['response_id'] ?? $item['response_en']);
        }

        return '';
    }

    /**
     * Retrieve a detailed narrative financial advisory report.
     *
     * @param string $topic
     * @param string $locale
     * @return string|null
     */
    protected function getNarrativeAnalysis(string $topic, string $locale): ?string
    {
        try {
            switch ($topic) {
                case 'total_klien':
                    $totalClients = \App\Models\Client::where('status', 'aktif')->count();
                    session()->put('last_data_query', (float) $totalClients);
                    return $locale === 'en'
                        ? "### 📊 Client Portfolio Analysis\n\n" .
                          "Currently, J&J GROUP is partnering with **{$totalClients} active clients**. From a financial risk perspective, distributing your client base across {$totalClients} distinct accounts reduces concentration risk. I advise conducting regular creditworthiness reviews to safeguard overall receivable quality."
                        : "### 📊 Analisis Portofolio Klien\n\n" .
                          "Sebagai Senior Financial Consultant, saya menginformasikan bahwa saat ini J&J GROUP memiliki hubungan kemitraan dengan **{$totalClients} klien aktif**. Dari perspektif manajemen risiko, pembagian portofolio pada {$totalClients} entitas ini meminimalkan risiko konsentrasi pendapatan (*concentration risk*). Rekomendasi saya adalah terus melakukan pemantauan berkala terhadap status keaktifan klien serta kelayakan kredit mereka untuk menghindari piutang macet.";

                case 'invoice_lunas':
                    $paidCount = \App\Models\Invoice::where('status', 'paid')->count();
                    $paidTotal = \App\Models\Invoice::where('status', 'paid')->sum('total');
                    session()->put('last_data_query', (float) $paidTotal);
                    return $locale === 'en'
                        ? "### 💰 Realized Inflow (Paid Invoices) Analysis\n\n" .
                          "Our database records **{$paidCount} paid invoices** amounting to **Rp " . number_format($paidTotal, 0, ',', '.') . "** in realized collections.\n\n" .
                          "**Financial Review:** This represents a strong billing collection rate and high liquidity inflow. A high collection speed reduces pressure on short-term working capital. I recommend allocating at least 15% of this realized cash directly to operational reserves before planning any capital-heavy expansions."
                        : "### 💰 Analisis Realisasi Pendapatan (Lunas)\n\n" .
                          "Berdasarkan peninjauan data keuangan terkini, J&J GROUP berhasil merealisasikan arus kas masuk sebesar **Rp " . number_format($paidTotal, 0, ',', '.') . "** dari total **{$paidCount} invoice** yang telah lunas.\n\n" .
                          "**Interpretasi Finansial:** Tingkat pelunasan ini menunjukkan efektivitas penagihan yang sangat baik serta kepatuhan bayar yang solid dari klien Anda. Guna memperkuat fondasi keuangan jangka pendek, saya menyarankan untuk menyisihkan sebagian dari pendapatan riil ini ke pos cadangan kas operasional sebelum dialokasikan kembali untuk belanja modal.";

                case 'invoice_overdue':
                    $overdueInvoices = \App\Models\Invoice::with('client')
                        ->whereIn('status', ['sent', 'pending', 'dp'])
                        ->where('due_date', '<', Carbon::now())
                        ->get();
                    $overdueAmount = $overdueInvoices->sum('total');
                    $overdueCount = $overdueInvoices->count();
                    session()->put('last_data_query', (float) $overdueAmount);
                    
                    $replyList = [];
                    foreach ($overdueInvoices as $inv) {
                        $replyList[] = "* **Invoice #{$inv->invoice_number}** - {$inv->client->nama_client} | **Rp " . number_format($inv->total, 0, ',', '.') . "** (Jatuh tempo: " . $inv->due_date->format('d M Y') . ")";
                    }
                    
                    $listStr = count($replyList) > 0 ? implode("\n", $replyList) : "- None";

                    return $locale === 'en'
                        ? "### ⚠️ Overdue Receivable Risk Analysis\n\n" .
                          "There are currently **{$overdueCount} overdue invoices** representing **Rp " . number_format($overdueAmount, 0, ',', '.') . "** in unpaid receivables:\n\n" .
                          $listStr . "\n\n" .
                          "**Advisory & Mitigation:** Having Rp " . number_format($overdueAmount, 0, ',', '.') . " tied up in overdue status presents a liquidity concern. I advise sending automated payment alerts, placing a temporary hold on deliverables for critical overdue accounts, and adjusting future payment terms to require upfront deposits."
                        : "### ⚠️ Analisis Risiko Piutang Tertahan (Overdue)\n\n" .
                          "Saya menemukan **{$overdueCount} invoice menunggak** dengan total dana tertahan sebesar **Rp " . number_format($overdueAmount, 0, ',', '.') . "**:\n\n" .
                          $listStr . "\n\n" .
                          "**Rekomendasi Taktis Consultant:** Piutang tertahan sebesar **Rp " . number_format($overdueAmount, 0, ',', '.') . "** berpotensi memperlambat rasio perputaran kas operasional J&J GROUP. Tindakan kuratif segera yang saya sarankan meliputi pengiriman surat pengingat resmi secara berkala, penghentian sementara penyerahan proyek berjalan untuk akun-akun kritis tersebut, serta penyesuaian klausul kontrak baru dengan opsi pembayaran bertahap (down payment lebih tinggi).";

                case 'arus_kas':
                    $stats = app(\App\Services\BusinessUnitReportingService::class)->getSummaryStats();
                    $revenue = $stats['total_revenue'];
                    $outstanding = $stats['total_outstanding'];
                    $pending = $stats['pending_outstanding'];
                    $overdue = $stats['overdue_outstanding'];
                    $rate = $stats['collection_rate'];
                    session()->put('last_data_query', (float) $revenue);

                    return $locale === 'en'
                        ? "### 💵 Cash Flow & Liquidity Advisory\n\n" .
                          "Here is the consolidated cash flow overview based on live operational records:\n" .
                          "* **Realized Inflows (Paid Invoices):** Rp " . number_format($revenue, 0, ',', '.') . "\n" .
                          "* **Total Receivables (Outstanding):** Rp " . number_format($outstanding, 0, ',', '.') . "\n" .
                          "  - *Active / Upcoming (Pending):* Rp " . number_format($pending, 0, ',', '.') . "\n" .
                          "  - *Delayed / Risk (Overdue):* Rp " . number_format($overdue, 0, ',', '.') . "\n" .
                          "* **Billing Collection Efficiency:** " . number_format($rate, 1) . "%\n\n" .
                          "**Strategic Financial Assessment:** An efficiency rate of **" . number_format($rate, 1) . "%** reflects a strong operational collection model. However, the Rp " . number_format($overdue, 0, ',', '.') . " in delayed/overdue outstanding needs close monitoring. To optimize cash flow, I advise setting aside a provision for bad debts and reviewing credit lines for high-value clients."
                        : "### 💵 Analisis Arus Kas & Likuiditas Perusahaan\n\n" .
                          "Berikut adalah rangkuman posisi arus kas operasional J&J GROUP yang diekstraksi secara real-time:\n" .
                          "* **Realisasi Arus Kas Masuk (Lunas):** Rp " . number_format($revenue, 0, ',', '.') . "\n" .
                          "* **Total Piutang Outstanding:** Rp " . number_format($outstanding, 0, ',', '.') . "\n" .
                          "  - *Dalam Termin (Pending):* Rp " . number_format($pending, 0, ',', '.') . "\n" .
                          "  - *Terlambat / Berisiko (Overdue):* Rp " . number_format($overdue, 0, ',', '.') . "\n" .
                          "* **Rasio Kolektibilitas Tagihan:** " . number_format($rate, 1) . "%\n\n" .
                          "**Analisis Konsultan:** Rasio kolektibilitas Anda saat ini berada di tingkat **" . number_format($rate, 1) . "%**, menandakan efisiensi penagihan yang cukup solid. Meskipun demikian, piutang tertahan sebesar **Rp " . number_format($overdue, 0, ',', '.') . "** harus segera dikelola agar tidak mengganggu modal kerja operasional. Saya menyarankan audit piutang mingguan dan pengetatan syarat kredit termin pembayaran bagi klien baru.";

                case 'tren_performa':
                    $trend = app(\App\Services\DataAggregatorService::class)->getRevenueTrend($locale);
                    $curr = $trend['current_revenue'];
                    $prev = $trend['previous_revenue'];
                    $growth = $trend['growth_percent'];
                    $insight = $trend['insight'];
                    session()->put('last_data_query', (float) $curr);

                    return $locale === 'en'
                        ? "### 📈 Month-over-Month (MoM) Growth Analysis\n\n" .
                          "Financial performance trend metrics:\n" .
                          "* **Previous Month Revenue:** Rp " . number_format($prev, 0, ',', '.') . "\n" .
                          "* **Current Month Revenue:** Rp " . number_format($curr, 0, ',', '.') . "\n" .
                          "* **Revenue Growth Rate:** " . number_format($growth, 1) . "%\n\n" .
                          "**Advisory Note:** {$insight}. " .
                          ($growth > 0 
                              ? "This positive performance signifies rising commercial activity or improved invoicing collection. I suggest reinvesting parts of these gains into growth-driving units." 
                              : "This downward trajectory requires urgent review of client acquisition pipelines and operating costs. We must identify underperforming business units and optimize pricing policies.")
                        : "### 📈 Analisis Pertumbuhan & Tren Pendapatan MoM\n\n" .
                          "Metrik performa finansial bulan ke bulan menunjukkan data berikut:\n" .
                          "* **Pendapatan Bulan Lalu:** Rp " . number_format($prev, 0, ',', '.') . "\n" .
                          "* **Pendapatan Bulan Ini:** Rp " . number_format($curr, 0, ',', '.') . "\n" .
                          "* **Tingkat Pertumbuhan:** " . number_format($growth, 1) . "%\n\n" .
                          "**Rekomendasi Konsultan Keuangan:** {$insight}. " .
                          ($growth > 0 
                              ? "Pertumbuhan positif ini mengindikasikan ekspansi bisnis yang solid. Momentum ini sebaiknya dipertahankan dengan mengoptimalkan penetrasi pasar pada sektor industri klien yang paling menguntungkan." 
                              : "Tren negatif ini mengindikasikan adanya perlambatan aktivitas bisnis. Saya menyarankan peninjauan kembali biaya operasional (cost control) serta merestrukturisasi strategi penawaran harga layanan.");
                
                case 'invoice_besok':
                    $data = app(\App\Services\DataAggregatorService::class)->getInvoicesDueTomorrow($locale);
                    session()->put('last_data_query', (float) $data['total']);
                    return $data['text'];
            }
        } catch (\Throwable $e) {
            Log::error("AiKnowledgeService getNarrativeAnalysis Error: " . $e->getMessage());
        }
        return null;
    }

    /**
     * Execute database queries for dynamic data triggers.
     *
     * @param string $queryType
     * @param string $locale
     * @return string
     */
    protected function executeDynamicQuery(string $queryType, string $locale): string
    {
        try {
            switch ($queryType) {
                case 'total_clients':
                    $totalClients = \App\Models\Client::where('status', 'aktif')->count();
                    session()->put('last_data_query', (float) $totalClients);
                    return $locale === 'en'
                        ? "Currently, the system records **{$totalClients} active clients**. You can view and manage client details fully on the Clients page."
                        : "Saat ini sistem mencatat Anda memiliki **{$totalClients} klien aktif**. Anda dapat melihat dan mengelola detail data klien secara lengkap di halaman Klien.";

                case 'paid_invoices':
                    $paidCount = \App\Models\Invoice::where('status', 'paid')->count();
                    $paidTotal = \App\Models\Invoice::where('status', 'paid')->sum('total');
                    session()->put('last_data_query', (float) $paidTotal);
                    return $locale === 'en'
                        ? "The total amount of paid invoices is **Rp " . number_format($paidTotal, 0, ',', '.') . "** from **{$paidCount} invoices**."
                        : "Total nominal tagihan yang telah lunas (paid) adalah **Rp " . number_format($paidTotal, 0, ',', '.') . "** dari total **{$paidCount} invoice**.";

                case 'overdue_invoices':
                    $overdueInvoices = \App\Models\Invoice::with('client')
                        ->whereIn('status', ['sent', 'pending', 'dp'])
                        ->where('due_date', '<', Carbon::now())
                        ->get();
                    $replyList = [];
                    foreach ($overdueInvoices as $inv) {
                        $replyList[] = "* **Invoice #{$inv->invoice_number}** oleh {$inv->client->nama_client} - Rp " . number_format($inv->total, 0, ',', '.') . " (Jatuh tempo: " . $inv->due_date->format('d M Y') . ")";
                    }
                    $overdueTotal = $overdueInvoices->sum('total');
                    session()->put('last_data_query', (float) $overdueTotal);
                    return $locale === 'en'
                        ? "Here is the list of overdue invoices:\n\n" . (count($replyList) > 0 ? implode("\n", $replyList) : "No overdue invoices at this moment.") . "\n\n**Action Recommendation:** Contact the respective clients immediately or use the AI billing email draft feature to secure payments."
                        : "Berikut adalah daftar invoice yang saat ini berstatus menunggak (overdue):\n\n" . (count($replyList) > 0 ? implode("\n", $replyList) : "Tidak ada invoice menunggak saat ini.") . "\n\n**Rekomendasi Tindakan:** Hubungi klien bersangkutan segera atau gunakan fitur Draf Email Penagihan AI untuk mengamankan pembayaran.";

                case 'receipts_index':
                    return $locale === 'en'
                        ? "You can view the receipt list or create new receipts from the receipts menu."
                        : "Anda dapat melihat daftar kuitansi atau membuat kuitansi baru melalui menu kuitansi.";

                case 'reports_index':
                    return $locale === 'en'
                        ? "The Reports page offers visual analysis on revenue, receivables, and historical business KPIs."
                        : "Halaman Laporan menyajikan visualisasi data yang mendalam mengenai pendapatan, piutang, dan statistik bisnis bulanan.";

                case 'chronos_index':
                    return $locale === 'en'
                        ? "Chronos Calendar lets you manage invoice timelines and collection deadlines interactively."
                        : "Kalender Chronos mempermudah Anda dalam memantau timeline invoice berdasarkan tanggal jatuh temponya secara interaktif.";

                case 'dashboard':
                    return $locale === 'en'
                        ? "On the Dashboard, you can monitor monthly metrics, outstanding dues, and cash flow insights."
                        : "Di halaman dashboard, Anda dapat memantau grafik penjualan bulanan, total tagihan outstanding, ringkasan aktivitas, serta analisis cashflow secara visual.";

                case 'revenue_trend':
                    $trend = app(\App\Services\DataAggregatorService::class)->getRevenueTrend($locale);
                    $curr  = $trend['current_revenue'];
                    $prev  = $trend['previous_revenue'];
                    $growth = $trend['growth_percent'];
                    $insight = $trend['insight'];
                    session()->put('last_data_query', (float) $curr);

                    return $locale === 'en'
                        ? "### 📈 Month-over-Month (MoM) Performance Analysis\n\n" .
                          "Here are J&J GROUP's revenue trend metrics for the current period:\n" .
                          "* **Previous Month Revenue:** Rp " . number_format($prev, 0, ',', '.') . "\n" .
                          "* **Current Month Revenue:** Rp " . number_format($curr, 0, ',', '.') . "\n" .
                          "* **Revenue Growth Rate (MoM):** " . number_format($growth, 1) . "%\n\n" .
                          "**Advisory Note:** {$insight}. " .
                          ($growth > 0
                              ? "This positive trend signals healthy commercial activity. I recommend reinvesting a portion of these gains into top-performing business units."
                              : "This downward trend warrants an urgent review of client acquisition pipelines and cost structures to prevent further erosion.")
                        : "### 📈 Analisis Performa & Tren Pendapatan MoM\n\n" .
                          "Berikut adalah metrik tren performa keuangan J&J GROUP berdasarkan data riil sistem:\n" .
                          "* **Pendapatan Bulan Lalu:** Rp " . number_format($prev, 0, ',', '.') . "\n" .
                          "* **Pendapatan Bulan Ini:** Rp " . number_format($curr, 0, ',', '.') . "\n" .
                          "* **Tingkat Pertumbuhan (MoM):** " . number_format($growth, 1) . "%\n\n" .
                          "**Rekomendasi Konsultan:** {$insight}. " .
                          ($growth > 0
                              ? "Pertumbuhan positif ini menunjukkan ekspansi bisnis yang solid. Pertahankan momentum dengan mengoptimalkan klien pada segmen paling menguntungkan."
                              : "Tren penurunan ini perlu dievaluasi segera. Saya menyarankan tinjauan cost control menyeluruh dan restrukturisasi strategi penawaran harga.");
                
                case 'arus_kas':
                    $stats = app(\App\Services\BusinessUnitReportingService::class)->getSummaryStats();
                    session()->put('last_data_query', (float) $stats['total_revenue']);
                    return $locale === 'en'
                        ? "J&J GROUP currently has **Rp " . number_format($stats['total_revenue'], 0, ',', '.') . "** in realized revenue, and **Rp " . number_format($stats['total_outstanding'], 0, ',', '.') . "** in outstanding receivables."
                        : "J&J GROUP saat ini mencatat pendapatan lunas sebesar **Rp " . number_format($stats['total_revenue'], 0, ',', '.') . "**, dengan total piutang outstanding yang belum diselesaikan sebesar **Rp " . number_format($stats['total_outstanding'], 0, ',', '.') . "**.";

                case 'invoice_due_tomorrow':
                    $data = app(\App\Services\DataAggregatorService::class)->getInvoicesDueTomorrow($locale);
                    session()->put('last_data_query', (float) $data['total']);
                    return $data['text'];
            }
        } catch (\Throwable $e) {
            Log::error("AiKnowledgeService Dynamic Query Error: " . $e->getMessage());
        }

        return $locale === 'en'
            ? "Sorry, data for that period is not yet available in the system."
            : "Maaf, data transaksi untuk periode tersebut belum tersedia di sistem.";
    }
}
