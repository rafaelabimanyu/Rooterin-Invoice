<?php

namespace App\Services;

class KnowledgeDictionary
{
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
                    'response_id' => "Selamat datang di J&J GROUP Command Center. Sebagai asisten bisnis Anda, berikut adalah struktur operasional sistem yang dapat Anda kelola secara langsung melalui menu navigasi di sidebar kiri:\n\n" .
                                     "1. **Dashboard**: Menampilkan grafik penjualan bulanan, tagihan outstanding, ringkasan aktivitas, serta analisis cashflow secara visual.\n" .
                                     "2. **AI Assistant**: Hub khusus tempat Anda dapat berinteraksi secara interaktif dengan asisten finansial lokal.\n" .
                                     "3. **Clients**: Modul manajemen kemitraan untuk pendaftaran dan pencatatan riwayat transaksi klien.\n" .
                                     "4. **Receipts**: Sistem pencatatan resmi kwitansi penerimaan kas yang sah setelah tagihan dilunasi.\n" .
                                     "5. **Invoices**: Area penerbitan invoice baru yang dilengkapi dengan asisten AI Copywriter untuk rancangan pesan penagihan.\n" .
                                     "6. **Chronos Calendar**: Kalender taktis pemantauan tanggal jatuh tempo piutang agar tidak terlewat.\n" .
                                     "7. **Owner KPI**: Analitik mendalam terkait efektivitas operasional, kecepatan penagihan, dan profit sharing.\n" .
                                     "8. **Reports**: Halaman pelaporan komprehensif bagi arus kas, profitabilitas unit bisnis, dan data ekspor.\n" .
                                     "9. **Team Management**: Pusat pembagian otoritas dan pengelolaan hak akses tim (Owner, Admin, Staff).\n" .
                                     "10. **Settings**: Konfigurasi umum aplikasi, profil perusahaan, dan data rekening bank.\n" .
                                     "11. **Security Center**: Gerbang pemantauan keamanan enkripsi data, kelola sesi aktif, serta aktivasi Autentikasi Dua Faktor (2FA).\n" .
                                     "12. **J&J GROUP Guide**: Dokumentasi SOP resmi panduan penggunaan sistem.",
                    'response_en' => "Welcome to the J&J GROUP Command Center. As your senior business advisor, here is the official menu structure available on your left sidebar panel:\n\n" .
                                     "1. **Dashboard**: Visualizes monthly revenue trends, outstanding balances, activity logs, and real-time cash flow.\n" .
                                     "2. **AI Assistant**: A dedicated page for strategic conversations with your virtual financial consultant.\n" .
                                     "3. **Clients**: Portal to manage client relations, company directories, and transaction histories.\n" .
                                     "4. **Receipts**: System to record official transaction receipts upon payment confirmation.\n" .
                                     "5. **Invoices**: Module for drafting bills, calculating taxes, and generating AI-powered billing notifications.\n" .
                                     "6. **Chronos Calendar**: Interactive scheduling board mapping due dates and collections.\n" .
                                     "7. **Owner KPI**: Executive metrics tracking staff performance, collection speed, and profit splits.\n" .
                                     "8. **Reports**: Financial analytics hub detailing cash flow records, business unit shares, and exports.\n" .
                                     "9. **Team Management**: Access control settings to distribute roles among Owner, Admin, and Staff.\n" .
                                     "10. **Settings**: Company configuration panel, core metadata, and bank accounts.\n" .
                                     "11. **Security Center**: Security controls for active sessions, encryption logs, and Two-Factor Authentication (2FA).\n" .
                                     "12. **J&J GROUP Guide**: Standard Operating Procedures and user manuals.",
                ],
                'buat_invoice' => [
                    'keywords' => ['buat invoice', 'tambah invoice', 'create invoice', 'bikin invoice', 'buat tagihan', 'bikin tagihan'],
                    'priority' => 8,
                    'response_id' => "Untuk menerbitkan tagihan (invoice) baru, Anda dapat membuka halaman Pembuatan Invoice. Saya sangat menyarankan Anda memeriksa detail termin pembayaran, diskon, dan PPN/PPh dengan teliti guna menghindari perselisihan pembayaran atau kesalahan pelaporan pajak di kemudian hari.",
                    'response_en' => "To issue a new invoice, please proceed to the Invoice creation page. I highly recommend validating payment terms, discounts, and applicable taxes (PPN/PPh) carefully to avoid future payment disputes or compliance errors.",
                    'navigate' => 'invoices.create',
                ],
                'buat_klien' => [
                    'keywords' => ['tambah klien', 'buat klien', 'create client', 'bikin klien', 'klien baru', 'tambah customer', 'bikin customer'],
                    'priority' => 8,
                    'response_id' => "Menambahkan klien baru merupakan langkah penting dalam ekspansi kemitraan bisnis. Anda dapat mendaftarkan profil lengkap klien termasuk nomor NPWP dan email utama pada menu Klien Baru agar alur komunikasi penagihan berjalan lancar.",
                    'response_en' => "Onboarding a new client represents an exciting expansion opportunity. You can register complete client details, tax credentials, and key contact addresses on the Client creation page to ensure clean invoicing operations.",
                    'navigate' => 'clients.create',
                ],
                'buat_kuitansi' => [
                    'keywords' => ['buat kuitansi', 'tambah kuitansi', 'create receipt', 'bikin kuitansi', 'buat receipt', 'tambah kwitansi'],
                    'priority' => 8,
                    'response_id' => "Pencatatan kuitansi adalah bukti penerimaan kas yang sah secara hukum. Setelah pembayaran terverifikasi masuk ke rekening perusahaan, segera terbitkan kuitansi resmi melalui menu Kuitansi Baru untuk menjaga keaslian audit keuangan Anda.",
                    'response_en' => "Generating receipts is the official documentation of cash realization. Once payments are verified, please issue a formal receipt from the Receipts module to maintain a solid corporate audit trail.",
                    'navigate' => 'receipts.create',
                ],
                'pengaturan' => [
                    'keywords' => ['buka pengaturan', 'setting aplikasi', 'konfigurasi sistem'],
                    'priority' => 5,
                    'response_id' => "Anda dapat mengonfigurasi profil perusahaan, detail rekening bank operasional, serta parameter sistem di halaman Pengaturan. Menyelaraskan pengaturan ini sangat penting agar invoice yang diterbitkan menampilkan data administrasi yang sah.",
                    'response_en' => "You can adjust corporate profiles, operating bank details, and system parameters on the Settings page. Keeping these settings updated ensures that all issued invoices contain compliant billing metadata.",
                    'navigate' => 'settings.index',
                ],
                'keamanan' => [
                    'keywords' => ['keamanan enkripsi', 'security center', '2fa authentication', 'two factor auth', 'keamanan data'],
                    'priority' => 7,
                    'response_id' => "Pusat Keamanan (Security Center) J&J GROUP didesain untuk menjamin integritas data Anda. Di sini, Anda dapat mengaktifkan Autentikasi Dua Faktor (2FA), memantau log audit enkripsi data sensitif, serta meninjau riwayat sesi masuk aktif.",
                    'response_en' => "The Security Center is built to safeguard your financial data. Within this module, you can activate Two-Factor Authentication (2FA), monitor sensitive data encryption logs, and review active user sessions.",
                    'navigate' => 'settings.index',
                ],
                'profil' => [
                    'keywords' => ['profil user', 'akun personal', 'user profile', 'data diri', 'ubah profil', 'edit profile', 'ubah password', 'edit kata sandi'],
                    'priority' => 6,
                    'response_id' => "Untuk memperbarui detail akun personal Anda, mengubah kata sandi, atau memperbarui foto profil, silakan buka halaman Profil Pengguna.",
                    'response_en' => "To update your personal account details, change passwords, or renew profile configurations, please visit your User Profile page.",
                    'navigate' => 'profile.edit',
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
                'kuitansi_list' => [
                    'keywords' => ['daftar kuitansi sistem', 'riwayat kwitansi pembayaran', 'receipts list'],
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
                ]
            ]
        ];
    }
}
