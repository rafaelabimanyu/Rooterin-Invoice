<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class RealisticBusinessSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Users
        User::create([
            'name' => 'Rooterin Owner',
            'email' => 'owner@rooterin.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'System Admin',
            'email' => 'admin@rooterin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Staff Member {$i}",
                'email' => "staff{$i}@rooterin.com",
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]);
        }

        // 2. Clients (Indonesian Companies & Diverse Profiles)
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
                'nama_perusahaan' => 'Klinik Medika Utama',
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

        // 3. Jobs/Services descriptions
        $jobDescriptions = [
            'Plumbing Repair - Kitchen Sink Leak',
            'Installation of Master Bathroom Piping',
            'Maintenance of HVAC System - Office Floor 2',
            'Emergency Pipe Burst Repair - Basement',
            'Monthly Technical Support & Maintenance',
            'Bathroom Renovation - Full Tiling',
            'Roof Leakage Fixing - South Wing',
            'Electrical Panel Board Service',
            'Installation of Water Filtration System',
            'Sanitization & Cleaning Services'
        ];

        // 4. Invoices & Items
        $clients = Client::all();
        $statuses = ['draft', 'sent', 'pending', 'dp', 'paid', 'overdue'];

        for ($i = 1; $i <= 40; $i++) {
            $client = $clients->random();
            $subtotal = $faker->numberBetween(5, 50) * 100000;
            $tax = $subtotal * 0.11;
            $total = $subtotal + $tax;
            $status = $faker->randomElement($statuses);
            $date = Carbon::now()->subDays($faker->numberBetween(1, 60));

            $invoice = Invoice::create([
                'invoice_number' => "ROOT-INV-" . str_pad($i, 5, '0', STR_PAD_LEFT),
                'client_id' => $client->id,
                'tanggal_invoice' => $date,
                'due_date' => $date->copy()->addDays(14),
                'status' => $status,
                'subtotal' => $subtotal,
                'tax_percent' => 11,
                'total' => $total,
                'notes_internal' => 'System generated seeder data.',
                'terms_condition' => 'Payment due within 14 days of issuance.',
                'created_by' => User::where('role', 'owner')->first()->id,
            ]);

            // Items
            $invoice->items()->create([
                'deskripsi' => $faker->randomElement($jobDescriptions),
                'qty' => $faker->numberBetween(1, 5),
                'harga' => $subtotal,
                'total' => $subtotal,
            ]);

            // Payments for paid/dp invoices
            if ($status === 'paid') {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'payment_date' => $date->addDays(2),
                    'amount' => $total,
                    'payment_method' => $faker->randomElement(['transfer', 'cash']),
                    'notes' => 'Settled in full.',
                ]);
            } elseif ($status === 'dp') {
                $dpAmount = $total * 0.3;
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'payment_date' => $date->addDays(1),
                    'amount' => $dpAmount,
                    'payment_method' => 'transfer',
                    'notes' => 'Downpayment 30%',
                ]);
            }
        }

        // 5. Receipts
        for ($i = 1; $i <= 10; $i++) {
            $client = $clients->random();
            $subtotal = $faker->numberBetween(10, 100) * 100000;
            $tax = $subtotal * 0.11;
            $total = $subtotal + $tax;

            Receipt::create([
                'receipt_number' => "ROOT-KWT-" . str_pad($i, 5, '0', STR_PAD_LEFT),
                'client_id' => $client->id,
                'tanggal_receipt' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'expiry_date' => Carbon::now()->addDays(30),
                'status' => $faker->randomElement(['sent', 'approved', 'rejected']),
                'subtotal' => $subtotal,
                'tax_percent' => 11,
                'total' => $total,
                'created_by' => User::where('role', 'owner')->first()->id,
            ]);
        }
    }
}
