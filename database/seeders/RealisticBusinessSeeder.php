<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\User;
use App\Models\Payment;
use App\Models\AiChatHistory;
use App\Models\ActivityLog;
use App\Models\SecurityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class RealisticBusinessSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Users & Team Management
        $usersData = [
            [
                'name' => 'J&J GROUP Owner',
                'email' => 'owner@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_OWNER,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'System Admin',
                'email' => 'admin@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Staff Member 1',
                'email' => 'staff1@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Staff Member 2',
                'email' => 'staff2@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'en',
            ],
            [
                'name' => 'Staff Member 3',
                'email' => 'staff3@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Roni Wijaya',
                'email' => 'roni@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Sarah Siregar',
                'email' => 'sarah@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'en',
            ],
            [
                'name' => 'Muhammad Fikri',
                'email' => 'fikri@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Clarissa Utama',
                'email' => 'clarissa@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'is_active' => 1,
                'locale' => 'id',
            ],
        ];

        foreach ($usersData as $data) {
            User::create($data);
        }

        $allUsers = User::all();

        // 2. Business Units Setup
        $businessUnitsData = [
            ['name' => 'Jaya-Website', 'slug' => 'jaya-website'],
            ['name' => 'Jaya-Sosmed', 'slug' => 'jaya-sosmed'],
            ['name' => 'Jaya-Operational', 'slug' => 'jaya-operational'],
        ];
        foreach ($businessUnitsData as $bu) {
            \App\Models\BusinessUnit::create($bu);
        }
        $businessUnits = \App\Models\BusinessUnit::all();

        // 3. Diverse Clients Setup
        $clientsData = [
            [
                'nama_client' => 'Budi Santoso',
                'nama_perusahaan' => 'Rumahan / Umum',
                'client_type' => 'individual',
                'industry_sector' => 'general',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama_client' => 'Siti Aminah',
                'nama_perusahaan' => 'Resto Selera Nusantara',
                'client_type' => 'individual',
                'industry_sector' => 'fnb',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'nama_client' => 'Dr. H. Faisal',
                'nama_perusahaan' => 'RSUD Harapan Bangsa',
                'client_type' => 'government',
                'industry_sector' => 'healthcare',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
            ],
            [
                'nama_client' => 'Prof. Joko Susilo',
                'nama_perusahaan' => 'Yayasan Pendidikan Mulia',
                'client_type' => 'Yayasan',
                'industry_sector' => 'education',
                'kota' => 'Semarang',
                'provinsi' => 'Jawa Tengah',
            ],
            [
                'nama_client' => 'John Doe',
                'nama_perusahaan' => 'Global Tech Solutions Inc.',
                'client_type' => 'foreign',
                'industry_sector' => 'tech',
                'kota' => 'Singapore',
                'provinsi' => 'Singapore',
            ],
            [
                'nama_client' => 'Andi Wijaya',
                'nama_perusahaan' => 'PT Solusi Digital Nusantara',
                'client_type' => 'corporate',
                'industry_sector' => 'tech',
                'kota' => 'Jakarta Barat',
                'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama_client' => 'Hendra Setiawan',
                'nama_perusahaan' => 'PT Sinar Agung Pertekstilan',
                'client_type' => 'corporate',
                'industry_sector' => 'manufacturing',
                'kota' => 'Solo',
                'provinsi' => 'Jawa Tengah',
            ],
            [
                'nama_client' => 'Supriyadi',
                'nama_perusahaan' => 'Pabrik Semen Perkasa',
                'client_type' => 'corporate',
                'industry_sector' => 'manufacturing',
                'kota' => 'Gresik',
                'provinsi' => 'Jawa Timur',
            ],
            [
                'nama_client' => 'drg. Ratih Rahmawati',
                'nama_perusahaan' => 'Klinik Gigi Medika Utama',
                'client_type' => 'corporate',
                'industry_sector' => 'healthcare',
                'kota' => 'Tangerang',
                'provinsi' => 'Banten',
            ],
            [
                'nama_client' => 'Reza Pahlevi',
                'nama_perusahaan' => 'Kopi Kenangan Senja Group',
                'client_type' => 'corporate',
                'industry_sector' => 'fnb',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama_client' => 'Bambang Pamungkas',
                'nama_perusahaan' => 'Koperasi Tani Makmur',
                'client_type' => 'Koperasi',
                'industry_sector' => 'Pertanian',
                'kota' => 'Malang',
                'provinsi' => 'Jawa Timur',
            ],
            [
                'nama_client' => 'Dewi Lestari',
                'nama_perusahaan' => 'Dinas Kesehatan Kota Bandung',
                'client_type' => 'government',
                'industry_sector' => 'healthcare',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'nama_client' => 'Agus Rahman',
                'nama_perusahaan' => 'Yayasan Kasih Ibu',
                'client_type' => 'Yayasan',
                'industry_sector' => 'healthcare',
                'kota' => 'Yogyakarta',
                'provinsi' => 'DI Yogyakarta',
            ],
            [
                'nama_client' => 'Wawan Kurniawan',
                'nama_perusahaan' => 'CV Abadi Sentosa',
                'client_type' => 'corporate',
                'industry_sector' => 'general',
                'kota' => 'Medan',
                'provinsi' => 'Sumatera Utara',
            ],
            [
                'nama_client' => 'Kenji Tanaka',
                'nama_perusahaan' => 'Tokyo Food Import Ltd',
                'client_type' => 'foreign',
                'industry_sector' => 'fnb',
                'kota' => 'Tokyo',
                'provinsi' => 'Japan',
            ],
            [
                'nama_client' => 'Sri Mulyani',
                'nama_perusahaan' => 'SD Negeri 01 Menteng',
                'client_type' => 'government',
                'industry_sector' => 'education',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama_client' => 'Rahmat Hidayat',
                'nama_perusahaan' => 'PT Megah Beton',
                'client_type' => 'corporate',
                'industry_sector' => 'manufacturing',
                'kota' => 'Makassar',
                'provinsi' => 'Sulawesi Selatan',
            ],
            [
                'nama_client' => 'Linda Kartika',
                'nama_perusahaan' => 'PT Dirgantara Aero',
                'client_type' => 'corporate',
                'industry_sector' => 'tech',
                'kota' => 'Depok',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'nama_client' => 'Achmad Zaky',
                'nama_perusahaan' => 'Klinik Gigi Dental Care',
                'client_type' => 'individual',
                'industry_sector' => 'healthcare',
                'kota' => 'Palembang',
                'provinsi' => 'Sumatera Selatan',
            ],
            [
                'nama_client' => 'Yusuf Mansur',
                'nama_perusahaan' => 'Katering Berkah Mandiri',
                'client_type' => 'individual',
                'industry_sector' => 'fnb',
                'kota' => 'Balikpapan',
                'provinsi' => 'Kalimantan Timur',
            ],
        ];

        foreach ($clientsData as $data) {
            Client::create([
                'kode_client' => Client::generateCode(),
                'nama_client' => $data['nama_client'],
                'nama_perusahaan' => $data['nama_perusahaan'],
                'client_type' => $data['client_type'],
                'industry_sector' => $data['industry_sector'],
                'email' => $faker->unique()->companyEmail,
                'no_hp' => '08' . $faker->numerify('##########'),
                'npwp' => $faker->numerify('##.###.###.#-###.###'),
                'alamat' => $faker->address,
                'kota' => $data['kota'],
                'provinsi' => $data['provinsi'],
                'status' => $faker->randomElement(['aktif', 'aktif', 'aktif', 'nonaktif']),
            ]);
        }

        $clients = Client::all();

        // 4. Service Descriptions for Invoices
        $jobDescriptions = [
            'Pembuatan & Integrasi Sistem Dashboard Utama',
            'Piping & Maintenance HVAC Tower A',
            'Emergency Pipeline Restoration & Repair',
            'Konsultasi Arsitektur Data Cloud AWS',
            'Sewa Server VPS Enterprise Bulanan',
            'Layanan Katering VIP Event Kemenkes',
            'Instalasi Panel Listrik & Panel Board',
            'Suplai Alat Medis RSUD Harapan Bangsa',
            'Implementasi AI Chatbot Layanan Pelanggan',
            'Pelatihan SDM & Workshop Digital Marketing',
            'Bahan Baku Semen & Konstruksi Site C',
            'Desain Menu & Media Promosi Kopi Group'
        ];

        // 5. Invoices & Billing Lifecycle
        $statuses = ['draft', 'sent', 'pending', 'paid', 'overdue', 'cancelled'];
        
        for ($i = 1; $i <= 30; $i++) {
            $client = $clients->random();
            $bu = $businessUnits->random();
            $subtotal = $faker->numberBetween(8, 120) * 200000;
            $discount = $faker->randomElement([0, 0, 50000, 100000]);
            $ppn = ($subtotal - $discount) * 0.11;
            $pph = ($subtotal - $discount) * 0.02;
            $total = ($subtotal - $discount) + $ppn - $pph;

            $status = $faker->randomElement($statuses);
            $date = Carbon::now()->subDays($faker->numberBetween(1, 90));
            $dueDate = $date->copy()->addDays($faker->randomElement([7, 14, 30]));

            if ($status === 'pending' && $dueDate->isPast()) {
                $status = 'overdue';
            }

            $invoice = Invoice::create([
                'invoice_number' => "INV-" . (5003 + $i) . "-2026",
                'business_unit_id' => $bu->id,
                'client_id' => $client->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'ppn' => $ppn,
                'pph' => $pph,
                'total' => $total,
                'status' => $status,
                'due_date' => $dueDate,
                'cause_of_problem' => $faker->randomElement(['Pasir dan Batu', 'Lemak dan Kerak', 'Tisu dan Pembalut', null]),
                'notes' => 'Pekerjaan ini telah diverifikasi langsung di lokasi oleh teknisi kami menggunakan peralatan presisi tinggi, sesuai dengan standar kualitas J&J GROUP.',
            ]);

            // Add Invoice Items
            $itemCount = $faker->numberBetween(1, 3);
            for ($j = 1; $j <= $itemCount; $j++) {
                $itemSub = $subtotal / $itemCount;
                $invoice->items()->create([
                    'deskripsi' => $faker->randomElement($jobDescriptions) . " - Bagian " . $j,
                    'qty' => 1,
                    'harga' => $itemSub,
                    'total' => $itemSub,
                ]);
            }

            // Generate Payments & Receipts for 'paid' status
            if ($status === 'paid') {
                Receipt::create([
                    'receipt_number' => "KWT-" . (5003 + $i) . "-2026",
                    'invoice_id' => $invoice->id,
                    'amount_received' => $total,
                    'payment_date' => $date->copy()->addDays($faker->numberBetween(1, 5)),
                ]);
            }
        }

        // 6. Additional Paid Invoices and Receipts
        for ($i = 1; $i <= 8; $i++) {
            $client = $clients->random();
            $bu = $businessUnits->random();
            $subtotal = $faker->numberBetween(10, 100) * 150000;
            $discount = 0;
            $ppn = $subtotal * 0.11;
            $pph = 0;
            $total = $subtotal + $ppn;
            $number = 30 + $i;

            $invoice = Invoice::create([
                'invoice_number' => "INV-" . (5003 + 30 + $i) . "-2026",
                'business_unit_id' => $bu->id,
                'client_id' => $client->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'ppn' => $ppn,
                'pph' => $pph,
                'total' => $total,
                'status' => 'paid',
                'due_date' => Carbon::now()->addDays(30),
                'cause_of_problem' => null,
                'notes' => 'Pekerjaan ini telah diverifikasi langsung di lokasi oleh teknisi kami menggunakan peralatan presisi tinggi, sesuai dengan standar kualitas J&J GROUP.',
            ]);

            $invoice->items()->create([
                'deskripsi' => 'Layanan Perawatan Teknis Tambahan',
                'qty' => 1,
                'harga' => $subtotal,
                'total' => $subtotal,
            ]);

            Receipt::create([
                'receipt_number' => "KWT-" . (5003 + 30 + $i) . "-2026",
                'invoice_id' => $invoice->id,
                'amount_received' => $total,
                'payment_date' => Carbon::now()->subDays($faker->numberBetween(1, 45)),
            ]);
        }

        // 7. AI Chat Histories & Telemetry Logs
        $aiMessages = [
            [
                'msg' => 'Berapa total piutang jatuh tempo bulan ini?',
                'res' => 'Total piutang jatuh tempo bulan ini adalah Rp 42.500.000 dari 4 invoice. Klien dengan tunggakan terbesar adalah RSUD Harapan Bangsa sebesar Rp 18.000.000.',
            ],
            [
                'msg' => 'Buatkan draft email pengingat pembayaran untuk klien Siti Aminah',
                'res' => "Berikut draft email yang bisa Anda kirim:\n\nSubjek: Pengingat Pembayaran Invoice #JNJ-INV-00012\n\nHalo Ibu Siti,\nKami ingin mengingatkan bahwa tagihan untuk layanan katering Resto Selera Nusantara sebesar Rp 8.400.000 telah melewati batas waktu pembayaran. Silakan lakukan pembayaran ke rekening BCA PT J&J Group Indonesia. Terima kasih.",
            ],
            [
                'msg' => 'Bagaimana performa arus kas J&J GROUP bulan ini?',
                'res' => 'Arus kas bulan ini sangat sehat dengan Collection Rate mencapai 84%. Penerimaan tunai utama berasal dari pelunasan invoice PT Solusi Digital Nusantara.',
            ],
            [
                'msg' => 'Analisis proyeksi keuangan 3 bulan ke depan',
                'res' => 'Berdasarkan data tagihan aktif dan tren historis, proyeksi pendapatan 3 bulan mendatang diperkirakan mencapai Rp 280.000.000 dengan resiko gagal bayar di bawah 5%.',
            ],
            [
                'msg' => 'Buat rekomendasi taktis mengatasi tagihan macet',
                'res' => "1. Terapkan denda keterlambatan 1% per minggu.\n2. Tawarkan cicilan pembayaran bertahap 3x bagi instansi pemerintah.\n3. Integrasikan AI Copywriter untuk otomatisasi email reminder H-3, H+1, dan H+7.",
            ]
        ];

        foreach ($allUsers as $user) {
            if ($user->role !== User::ROLE_STAFF) {
                for ($session = 1; $session <= 2; $session++) {
                    $sessionId = 'sess_' . $faker->bothify('??##??##');
                    foreach ($aiMessages as $chat) {
                        if ($faker->boolean(70)) {
                            AiChatHistory::create([
                                'user_id' => $user->id,
                                'session_id' => $sessionId,
                                'message' => $chat['msg'],
                                'response' => $chat['res'],
                                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 10)),
                            ]);
                        }
                    }
                }
            }
        }

        // 8. Activity & Security Logs
        $actions = [
            'login' => 'Telah masuk ke dalam aplikasi.',
            'create_invoice' => 'Membuat invoice baru dengan nomor JNJ-INV-',
            'update_invoice' => 'Memperbarui status invoice JNJ-INV-',
            'create_client' => 'Mendaftarkan klien baru ',
            'create_receipt' => 'Menerbitkan kwitansi baru JNJ-KWT-',
        ];

        for ($k = 1; $k <= 40; $k++) {
            $user = $allUsers->random();
            $actionKey = array_rand($actions);
            $actionText = $actions[$actionKey];

            if ($actionKey === 'create_invoice' || $actionKey === 'update_invoice') {
                $actionText .= str_pad($faker->numberBetween(1, 30), 5, '0', STR_PAD_LEFT);
            } elseif ($actionKey === 'create_client') {
                $actionText .= $clients->random()->nama_client;
            } elseif ($actionKey === 'create_receipt') {
                $actionText .= str_pad($faker->numberBetween(1, 10), 5, '0', STR_PAD_LEFT);
            }

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $actionKey,
                'description' => $user->name . ' ' . $actionText,
                'ip_address' => $faker->ipv4,
                'created_at' => Carbon::now()->subHours($faker->numberBetween(1, 100)),
            ]);

            if ($actionKey === 'login') {
                SecurityLog::create([
                    'user_id' => $user->id,
                    'activity' => "User Login: {$user->name} logged in successfully.",
                    'ip_address' => $faker->ipv4,
                    'user_agent' => $faker->userAgent,
                    'location' => $faker->city,
                    'is_suspicious' => false,
                    'created_at' => Carbon::now()->subHours($faker->numberBetween(1, 100)),
                ]);
            }
        }

        for ($k = 1; $k <= 3; $k++) {
            SecurityLog::create([
                'user_id' => null,
                'activity' => 'Failed login attempt using email: hacker' . $k . '@hacker.com',
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'location' => $faker->city,
                'is_suspicious' => true,
                'created_at' => Carbon::now()->subHours($faker->numberBetween(1, 50)),
            ]);
        }

        // 9. Custom Chronos Operational Events & Reminders
        \App\Models\ChronosEvent::create([
            'title' => 'Pengerjaan Fitur A & B (Internal Dev)',
            'description' => 'Selesaikan layout mobile & API untuk client management.',
            'start_date' => Carbon::create(2026, 5, 25),
            'end_date' => Carbon::create(2026, 5, 27),
            'color' => 'indigo',
            'status_type' => 'internal',
            'responsible_staff_id' => $allUsers->firstWhere('role', 'owner')?->id,
        ]);

        \App\Models\ChronosEvent::create([
            'title' => 'Meeting Bersama Tim Finansial Klien',
            'description' => 'Diskusi outstanding receivables dan workflow kuitansi.',
            'start_date' => Carbon::create(2026, 5, 30),
            'end_date' => Carbon::create(2026, 5, 30),
            'color' => 'emerald',
            'status_type' => 'meeting',
            'responsible_staff_id' => $allUsers->firstWhere('role', 'admin')?->id,
        ]);

        \App\Models\ChronosEvent::create([
            'title' => 'Implementasi Fitur AI Analytics Upgrade',
            'description' => 'Integrasikan parser Markdown & dynamic charts.',
            'start_date' => Carbon::create(2026, 6, 10),
            'end_date' => Carbon::create(2026, 6, 12),
            'color' => 'amber',
            'status_type' => 'draft',
            'responsible_staff_id' => $allUsers->firstWhere('role', 'owner')?->id,
        ]);
    }
}
