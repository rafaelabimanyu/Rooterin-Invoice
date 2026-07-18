<?php

namespace App\Services;

class KnowledgeDictionary
{
    /**
     * Centralized synonym map: raw user words → canonical form.
     * Processed BEFORE any fuzzy / alias matching.
     *
     * @return array<string, string>
     */
    public static function getSynonyms(): array
    {
        return [
            // Revenue / income synonyms
            'omset'        => 'pendapatan',
            'omzet'        => 'pendapatan',
            'penghasilan'  => 'pendapatan',
            'revenue'      => 'pendapatan',
            'income'       => 'pendapatan',
            'pemasukan'    => 'pendapatan',

            // Client synonyms
            'client'       => 'klien',
            'clients'      => 'klien',
            'customer'     => 'klien',
            'pelanggan'    => 'klien',
            'mitra'        => 'klien',

            // Business Unit synonyms
            'unit bisnis'   => 'unit bisnis',
            'business unit' => 'unit bisnis',
            'divisi'        => 'unit bisnis',
            'division'      => 'unit bisnis',

            // Receipt synonyms
            'kuitansi'     => 'kwitansi',
            'kwitansi'     => 'kwitansi',
            'receipt'      => 'kwitansi',
            'receipts'     => 'kwitansi',

            // Cash flow synonyms
            'cash flow'    => 'arus kas',
            'cashflow'     => 'arus kas',
            'aliran kas'   => 'arus kas',
            'keuangan'     => 'laporan',

            // Performance synonyms
            'analisa'      => 'analisis',
            'kinerja'      => 'performa',

            // Action synonyms
            'bikin'        => 'buat',
            'setting'      => 'pengaturan',
            'config'       => 'pengaturan',
            'setup'        => 'pengaturan',

            // Billing / invoice synonyms
            'tagihan'      => 'invoice',
        ];
    }

    /**
     * Get the structured dictionary of knowledge items.
     *
     * @return array
     */
    public static function getDictionary(): array
    {
        return [
            'static_procedures' => [
                'panduan_sistem' => [
                    'keywords' => ['panduan sistem', 'sop operasional', 'petunjuk penggunaan', 'guidebook', 'tutorial sistem', 'cara pakai aplikasi', 'halaman menu navigasi', 'fitur aplikasi', 'role hak akses'],
                    'priority' => 10,
                    'response_id' => "Panduan Sistem adalah modul untuk memandu penggunaan aplikasi, SOP operasional, serta pemetaan modul J&J GROUP. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "System Guide is a module to guide application usage, operational SOPs, and module mappings of J&J GROUP. You can access it via the button below.",
                    'navigate' => 'settings.index',
                ],
                'buat_invoice' => [
                    'keywords' => ['buat invoice', 'tambah invoice', 'create invoice', 'bikin invoice', 'buat tagihan', 'bikin tagihan'],
                    'priority' => 8,
                    'response_id' => "Pembuatan Invoice adalah modul untuk menerbitkan tagihan baru, merancang termin pembayaran, serta menghitung PPN/PPh secara akurat. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Invoice Creation is a module to issue new invoices, customize payment terms, and calculate tax details accurately. You can access it via the button below.",
                    'navigate' => 'invoices.create',
                ],
                'buat_klien' => [
                    'keywords' => ['tambah klien', 'buat klien', 'create client', 'bikin klien', 'klien baru', 'tambah customer', 'bikin customer'],
                    'priority' => 8,
                    'response_id' => "Pendaftaran Klien Baru adalah modul untuk mencatat profil lengkap mitra bisnis eksternal, NPWP, dan kontak penagihan utama. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Client Onboarding is a module to record external partner profiles, tax credentials, and billing contacts. You can access it via the button below.",
                    'navigate' => 'clients.create',
                ],
                'buat_kwitansi' => [
                    'keywords' => ['buat kwitansi', 'tambah kwitansi', 'create receipt', 'bikin kwitansi', 'buat receipt', 'buat kuitansi', 'tambah kuitansi', 'bikin kuitansi'],
                    'priority' => 8,
                    'response_id' => "Penerbitan Kwitansi adalah modul untuk mencatat bukti transaksi pembayaran kas masuk yang sah setelah invoice dilunasi oleh klien. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Receipt Issuance is a module to record cash inflows and payment confirmations once invoices are settled. You can access it via the button below.",
                    'navigate' => 'receipts.create',
                ],
                'pengaturan' => [
                    'keywords' => ['buka pengaturan', 'setting aplikasi', 'konfigurasi sistem'],
                    'priority' => 5,
                    'response_id' => "Pengaturan Sistem adalah modul untuk mengonfigurasi profil perusahaan J&J GROUP, rekening bank operasional, dan parameter aplikasi. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "System Settings is a module to configure J&J GROUP corporate profile, operational bank details, and system parameters. You can access it via the button below.",
                    'navigate' => 'settings.index',
                ],
                'keamanan' => [
                    'keywords' => ['keamanan enkripsi', 'security center', '2fa authentication', 'two factor auth', 'keamanan data'],
                    'priority' => 7,
                    'response_id' => "Pusat Keamanan adalah modul untuk mengaktifkan Autentikasi Dua Faktor (2FA), mengaudit log enkripsi data sensitif, dan memantau sesi masuk aktif tim. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Security Center is a module to enable Two-Factor Authentication (2FA), audit encryption logs, and monitor active login sessions. You can access it via the button below.",
                    'navigate' => 'security.center',
                ],
                'profil' => [
                    'keywords' => ['profil user', 'akun personal', 'user profile', 'data diri', 'ubah profil', 'edit profile', 'ubah password', 'edit kata sandi'],
                    'priority' => 6,
                    'response_id' => "Profil Pengguna adalah modul untuk memperbarui detail akun personal Anda, mengubah kata sandi, dan memperbarui foto profil. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "User Profile is a module to update your personal credentials, change passwords, and renew profile pictures. You can access it via the button below.",
                    'navigate' => 'profile.edit',
                ],
                'kalender_chronos' => [
                    'keywords' => ['kalender chronos', 'chronos calendar', 'kalender billing', 'jadwal jatuh tempo', 'kalender schedule'],
                    'priority' => 8,
                    'response_id' => "Kalender Chronos adalah modul untuk memantau timeline invoice, melacak tanggal jatuh tempo penagihan, dan deadline kolektibilitas secara interaktif. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Chronos Calendar is a module to interactively monitor invoice timelines, billing due dates, and collection deadlines. You can access it via the button below.",
                    'navigate' => 'chronos.index',
                ],
                'unit_bisnis' => [
                    'keywords' => ['unit bisnis', 'business units', 'daftar unit bisnis', 'manajemen unit bisnis'],
                    'priority' => 8,
                    'response_id' => "Manajemen Unit Bisnis adalah modul untuk mengonfigurasi divisi internal J&J GROUP beserta persentase fee sharing masing-masing unit. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Business Units is a module to configure J&J GROUP internal divisions and their respective fee sharing rates. You can access it via the button below.",
                    'navigate' => 'business-units.index',
                ],
                'manajemen_tim' => [
                    'keywords' => ['manajemen tim', 'kelola tim', 'team management', 'manajemen user', 'daftar pengguna'],
                    'priority' => 8,
                    'response_id' => "Manajemen Tim adalah modul untuk mengatur hak akses staf (Owner, Admin, Staff) serta memantau keaktifan akun tim dalam organisasi. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Team Management is a module to configure staff access permissions (Owner, Admin, Staff) and monitor account status. You can access it via the button below.",
                    'navigate' => 'users.index',
                ],
                'laporan_keuangan' => [
                    'keywords' => ['laporan keuangan', 'financial reports', 'ekspor laporan', 'laporan bulanan'],
                    'priority' => 8,
                    'response_id' => "Laporan Keuangan adalah modul untuk menganalisis grafik pendapatan bulanan, outstanding piutang, dan mengekspor laporan keuangan. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Financial Reports is a module to analyze monthly revenue trends, track outstanding receivables, and export financial datasets. You can access it via the button below.",
                    'navigate' => 'reports.index',
                ],
                'owner_kpi' => [
                    'keywords' => ['owner kpi', 'kpi owner', 'statistik owner', 'performa kpi'],
                    'priority' => 8,
                    'response_id' => "Owner KPI adalah modul untuk memantau keunggulan operasional, menghitung pembagian profit sharing owner secara otomatis, serta kecepatan rata-rata penagihan. Anda dapat mengaksesnya melalui tombol di bawah.",
                    'response_en' => "Owner KPI is a module to monitor operational efficiency, calculate owner profit sharing splits, and analyze collection velocity. You can access it via the button below.",
                    'navigate' => 'owner.kpi',
                ]
            ],
            'dynamic_data_triggers' => [
                'total_klien' => [
                    'keywords' => ['jumlah klien aktif', 'total klien aktif', 'daftar klien perusahaan', 'list klien aktif'],
                    'priority' => 9,
                    'query_type' => 'total_clients',
                    'navigate' => 'clients.index',
                ],
                'invoice_lunas' => [
                    'keywords' => ['total invoice lunas', 'tagihan paid', 'jumlah pembayaran lunas', 'pembayaran berhasil masuk', 'nominal invoice lunas'],
                    'priority' => 9,
                    'query_type' => 'paid_invoices',
                    'navigate' => 'invoices.index',
                ],
                'invoice_overdue' => [
                    'keywords' => ['invoice menunggak', 'tagihan overdue', 'tunggakan invoice', 'belum lunas', 'invoice macet', 'total overdue'],
                    'priority' => 9,
                    'query_type' => 'overdue_invoices',
                    'navigate' => 'invoices.index',
                ],
                'kwitansi_list' => [
                    'keywords' => ['daftar kwitansi sistem', 'riwayat kwitansi pembayaran', 'receipts list', 'daftar kuitansi sistem', 'riwayat kuitansi pembayaran'],
                    'priority' => 7,
                    'query_type' => 'receipts_index',
                    'navigate' => 'receipts.index',
                ],
                'laporan_keuangan' => [
                    'keywords' => ['laporan keuangan bulanan', 'analisa keuangan J&J', 'statistik keuangan'],
                    'priority' => 8,
                    'query_type' => 'reports_index',
                    'navigate' => 'reports.index',
                ],
                'kalender_billing' => [
                    'keywords' => ['kalender billing', 'chronos calendar scheduler', 'kalender jatuh tempo'],
                    'priority' => 8,
                    'query_type' => 'chronos_index',
                    'navigate' => 'chronos.index',
                ],
                'dashboard_info' => [
                    'keywords' => ['dashboard command center', 'ringkasan beranda', 'dashboard utama'],
                    'priority' => 7,
                    'query_type' => 'dashboard',
                    'navigate' => 'dashboard',
                ],
                'tren_performa' => [
                    'keywords' => [
                        'performa bulan ini', 'performa bisnis', 'performa pendapatan',
                        'pertumbuhan pendapatan', 'pertumbuhan bisnis',
                        'tren performa', 'tren pendapatan', 'revenue trend',
                        'growth trend', 'growth revenue', 'perkembangan pendapatan',
                        'analisis performa', 'bagaimana performa', 'how is performance',
                        'MoM growth', 'bulan ke bulan',
                    ],
                    'priority' => 9,
                    'query_type' => 'revenue_trend',
                    'navigate' => 'reports.index',
                ],
                'arus_kas' => [
                    'keywords' => [
                        'arus kas', 'cashflow', 'cash flow',
                        'aliran kas', 'posisi kas', 'likuiditas',
                        'analisis arus kas', 'cash flow statement', 'piutang perusahaan',
                        'posisi kas operasional', 'aliran kas masuk',
                    ],
                    'priority' => 9,
                    'query_type' => 'arus_kas',
                    'navigate' => 'reports.index',
                ],
                'invoice_besok' => [
                    'keywords' => [
                        'jatuh tempo besok', 'invoice besok', 'tagihan besok',
                        'due tomorrow', 'invoices due tomorrow',
                        'invoice jatuh tempo besok', 'tagihan jatuh tempo besok',
                    ],
                    'priority' => 10,
                    'query_type' => 'invoice_due_tomorrow',
                    'navigate' => 'invoices.index',
                ],
                // ─── REVENUE / OMSET TRIGGERS ───────────────────────────────
                'revenue_trend' => [
                    'keywords' => [
                        'pendapatan', 'omset', 'omzet', 'penghasilan', 'pemasukan',
                        'revenue', 'income',
                        'total pendapatan', 'total omset', 'total omzet',
                        'berapa pendapatan', 'berapa omset', 'berapa omzet',
                        'pendapatan bulan ini', 'omset bulan ini', 'omzet bulan ini',
                        'tren pendapatan', 'tren omset', 'pertumbuhan pendapatan',
                        'pertumbuhan omset', 'revenue trend', 'growth revenue',
                        'omset kotor', 'omset bersih', 'omzet kotor', 'omzet bersih',
                        'pendapatan kotor', 'pendapatan bersih', 'pendapatan kotor',
                        'gross revenue', 'net revenue'
                    ],
                    'priority' => 9,
                    'query_type' => 'revenue_trend',
                    'navigate' => 'reports.index',
                ],
                // ─── CLIENT PORTFOLIO TRIGGERS ──────────────────────────────
                'client_portfolio' => [
                    'keywords' => [
                        'klien', 'client', 'clients', 'customer', 'pelanggan',
                        'mitra',
                        'portofolio klien', 'daftar klien', 'list klien',
                        'jumlah klien', 'total klien', 'berapa klien',
                        'klien aktif', 'client aktif', 'semua klien',
                    ],
                    'priority' => 9,
                    'query_type' => 'total_clients',
                    'navigate' => 'clients.index',
                ]
            ]
        ];
    }
}
