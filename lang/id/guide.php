<?php

return [
    'title' => 'Panduan',
    'roles' => [
        'staff' => [
            'header' => [
                'title' => 'SOP Operasional Harian',
                'subtitle' => 'Panduan eksekusi harian untuk memastikan efisiensi dan akurasi data operasional.',
                'icon' => 'book-open',
            ],
            'workflow' => [
                ['label' => 'Input Klien', 'step' => 'Step 1', 'desc' => 'Registrasi NPWP & Data Klien'],
                ['label' => 'Quotation', 'step' => 'Step 2', 'desc' => 'Penawaran Harga'],
                ['label' => 'Invoice', 'step' => 'Step 3', 'desc' => 'Rilis Tagihan'],
                ['label' => 'Lunas', 'step' => 'Step 4', 'desc' => 'Catat Pembayaran'],
            ],
            'navigation' => [
                'getting-started' => [
                    'title' => 'Memulai',
                    'icon' => 'zap',
                    'color' => 'indigo',
                    'content' => 'Rooterin-Invoice dirancang untuk mempermudah alur kerja harian Anda. Pastikan Anda selalu memasukkan data dengan teliti dan mengikuti prosedur yang telah ditetapkan.',
                    'pro_tip' => 'Gunakan fitur "Inline Client Creation" saat membuat Invoice agar proses penagihan lebih cepat!',
                    'sub_sections' => []
                ],
                'invoices' => [
                    'title' => 'Pembuatan Invoice',
                    'icon' => 'file-text',
                    'color' => 'emerald',
                    'content' => 'SOP harian step-by-step pembuatan invoice dan manajemen tanda terima (receipts).',
                    'sub_sections' => [
                        'sop-pembuatan' => [
                            'title' => 'SOP Pembuatan Tagihan',
                            'content' => 'Pastikan klien sudah terdaftar. Masukkan detail item dengan deskripsi jelas. Cek kalkulasi otomatis PPN sebelum menekan tombol Save.'
                        ],
                        'validasi-pdf' => [
                            'title' => 'Validasi Sebelum PDF',
                            'content' => 'Checklist validasi: Cek ulang nama entitas perusahaan, alamat penagihan, dan nominal terbilang (spellout) agar minim kesalahan sebelum dikirim ke client.'
                        ]
                    ]
                ],
                'client-followup' => [
                    'title' => 'Prosedur Follow-up',
                    'icon' => 'users',
                    'color' => 'sky',
                    'content' => 'Panduan untuk melakukan follow-up pada klien yang menunggak.',
                    'sub_sections' => [
                        'reminder-email' => [
                            'title' => 'Pengiriman Reminder',
                            'content' => 'Jika status invoice melewati due date, gunakan tombol "Send Reminder" untuk mengirimkan notifikasi otomatis ke email klien.'
                        ]
                    ]
                ],
                'reports' => [
                    'title' => 'Laporan Harian',
                    'icon' => 'file-spreadsheet',
                    'color' => 'amber',
                    'content' => 'Cara melakukan generate laporan penerimaan kas harian pada penutupan shift.',
                    'sub_sections' => []
                ],
            ]
        ],
        'admin' => [
            'header' => [
                'title' => 'Panduan Manajemen Sistem',
                'subtitle' => 'Panduan manajemen pengguna, resolusi konflik, dan integritas data sistem.',
                'icon' => 'sliders',
            ],
            'workflow' => [
                ['label' => 'Audit Data', 'step' => 'Step 1', 'desc' => 'Cek Integritas'],
                ['label' => 'Sync Invoice', 'step' => 'Step 2', 'desc' => 'Resolusi Konflik'],
                ['label' => 'Manage Users', 'step' => 'Step 3', 'desc' => 'Kontrol Akses'],
                ['label' => 'Backup', 'step' => 'Step 4', 'desc' => 'Pencadangan Data'],
            ],
            'navigation' => [
                'user-management' => [
                    'title' => 'Pengelolaan Akun',
                    'icon' => 'users-cog',
                    'color' => 'indigo',
                    'content' => 'Prosedur keamanan untuk menambah Staff, mereset password, dan menonaktifkan akun yang mencurigakan.',
                    'sub_sections' => [
                        'staff-access' => [
                            'title' => 'Hak Akses Staff',
                            'content' => 'Berikan hak akses hanya sesuai scope pekerjaan. Segera cabut akses jika staff telah nonaktif.'
                        ]
                    ]
                ],
                'data-integrity' => [
                    'title' => 'Integritas & Sync Data',
                    'icon' => 'database',
                    'color' => 'emerald',
                    'content' => 'Langkah-langkah teknis troubleshooting jika terjadi error sinkronisasi.',
                    'sub_sections' => [
                        'invoice-conflict' => [
                            'title' => 'Konflik Nomor Invoice',
                            'content' => 'Jika terjadi duplikasi penomoran invoice, akses Master Data dan sesuaikan running number ke urutan tertinggi terakhir.'
                        ],
                        'cancellation' => [
                            'title' => 'Pembatalan Transaksi',
                            'content' => 'Transaksi yang sudah tervalidasi tidak boleh dihapus (hard-delete). Gunakan fitur "Void" agar tetap terekam di audit trail.'
                        ]
                    ]
                ],
                'master-data' => [
                    'title' => 'Pengaturan Master Data',
                    'icon' => 'layers',
                    'color' => 'amber',
                    'content' => 'Kelola kategori produk, satuan unit, dan pengaturan daftar harga standar (Price List).',
                    'sub_sections' => []
                ],
                'backup' => [
                    'title' => 'Pencadangan Manual',
                    'icon' => 'hard-drive',
                    'color' => 'slate',
                    'content' => 'Cara melakukan pencadangan database manual di luar siklus pencadangan otomatis mingguan.',
                    'sub_sections' => []
                ]
            ]
        ],
        'owner' => [
            'header' => [
                'title' => 'Panduan Strategis Eksekutif',
                'subtitle' => 'Dokumentasi komprehensif untuk pengawasan profitabilitas, legalitas, dan keputusan strategis.',
                'icon' => 'briefcase',
            ],
            'workflow' => [
                ['label' => 'Monitor KPI', 'step' => 'Step 1', 'desc' => 'Analisis Metrik'],
                ['label' => 'Review Cashflow', 'step' => 'Step 2', 'desc' => 'Kesehatan Finansial'],
                ['label' => 'Tax Config', 'step' => 'Step 3', 'desc' => 'Regulasi Perpajakan'],
                ['label' => 'Audit Trail', 'step' => 'Step 4', 'desc' => 'Keamanan Sistem'],
            ],
            'navigation' => [
                'owner-kpi' => [
                    'title' => 'Analisis Owner KPI',
                    'icon' => 'pie-chart',
                    'color' => 'emerald',
                    'content' => 'Panduan interpretasi data KPI pada dashboard untuk pengambilan keputusan strategis.',
                    'pro_tip' => 'Jika "Amount Due" melebihi 30% dari "Total Billing", segera instruksikan admin untuk penagihan agresif.',
                    'sub_sections' => [
                        'profitability' => [
                            'title' => 'Mengukur Profitabilitas',
                            'content' => 'Pelajari cara membedakan antara Gross Revenue dan Net Collection. Sistem menggunakan basis kas dan akrual secara simultan untuk metrik dashboard.'
                        ]
                    ]
                ],
                'financial-reports' => [
                    'title' => 'Laporan Keuangan',
                    'icon' => 'bar-chart-2',
                    'color' => 'violet',
                    'content' => 'Penjelasan mendalam tentang bagaimana data finansial dikalkulasi untuk menyusun Laporan Tahunan perusahaan.',
                    'sub_sections' => [
                        'tax-configuration' => [
                            'title' => 'Konfigurasi Perpajakan (Tax)',
                            'content' => 'Atur base percentage untuk PPN dan PPh. Sistem akan melakukan compounding otomatis pada level invoice items.'
                        ],
                        'profit-loss' => [
                            'title' => 'Profit & Loss (P&L)',
                            'content' => 'Memahami komponen pengurang pajak pada P&L statement.'
                        ]
                    ]
                ],
                'integrations' => [
                    'title' => 'Payment Gateway',
                    'icon' => 'credit-card',
                    'color' => 'blue',
                    'content' => 'Manajemen API Key untuk integrasi Xendit/Midtrans dan memantau status webhook settlement.',
                    'sub_sections' => []
                ],
                'audit-trail' => [
                    'title' => 'Audit Trail & Legalitas',
                    'icon' => 'shield-alert',
                    'color' => 'rose',
                    'content' => 'Pelacakan aktivitas tingkat tinggi (melacak setiap klik dan perubahan data oleh Admin/Staff) demi menjaga kepatuhan hukum.',
                    'sub_sections' => [
                        'license-management' => [
                            'title' => 'Pengelolaan Lisensi',
                            'content' => 'Status lisensi Rooterin Enterprise Anda dan pembaruan masa aktif server.'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
