<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\BusinessUnit;
use App\Models\Payment;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
 
class ContractInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create B2B Clients
        $client1 = Client::firstOrCreate(
            ['nama_client' => 'RS Abdi Waluyo'],
            [
                'kode_client' => Client::generateCode(),
                'nama_perusahaan' => 'PT Abdi Waluyo Medika',
                'client_type' => 'corporate',
                'industry_sector' => 'healthcare',
                'alamat' => 'Jl. Kaliurang No. 31, Menteng',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'status' => 'aktif',
            ]
        );
 
        $client2 = Client::firstOrCreate(
            ['nama_client' => 'Sushi Tei Indonesia'],
            [
                'kode_client' => Client::generateCode(),
                'nama_perusahaan' => 'PT Sushi Tei Indonesia',
                'client_type' => 'corporate',
                'industry_sector' => 'fnb',
                'alamat' => 'Plaza Indonesia, Jl. M.H. Thamrin No. 28-30',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'status' => 'aktif',
            ]
        );
 
        $client3 = Client::firstOrCreate(
            ['nama_client' => 'Kantor Imigrasi Jakarta Selatan'],
            [
                'kode_client' => Client::generateCode(),
                'nama_perusahaan' => 'Direktorat Jenderal Imigrasi Kemenkumham',
                'client_type' => 'government',
                'industry_sector' => 'government',
                'alamat' => 'Jl. Warung Buncit Raya No. 207',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'status' => 'aktif',
            ]
        );
 
        // 2. Fetch Business Units
        $unitJabodetabek = BusinessUnit::where('slug', 'like', '%jabodetabek%')->first() 
            ?: BusinessUnit::firstOrCreate(['name' => 'JAYA-JABODETABEK', 'slug' => 'jaya-jabodetabek']);
        $unitBogor = BusinessUnit::where('slug', 'like', '%bogor%')->first() 
            ?: BusinessUnit::firstOrCreate(['name' => 'JAYA-BOGOR', 'slug' => 'jaya-bogor']);
 
        $year = date('Y');
 
        // Data for Partnership Invoices
        $invoicesData = [
            [
                'invoice_number' => "INV-KMT-1001-{$year}",
                'business_unit_id' => $unitJabodetabek->id,
                'client_id' => $client1->id,
                'kategori_invoice' => 'kemitraan',
                'periode_kontrak' => 'Maintenance April - Juni 2026',
                'due_date' => Carbon::now()->addDays(14),
                'status' => 'paid',
                'cause_of_problem' => 'Routine preventive maintenance and main drainage line inspection.',
                'technician_names' => 'Rahmat Hidayat, Budi Santoso',
                'warranty' => '3 Bulan',
                'notes' => 'Contract Billing Q2 2026 - Hospital Sewer System.',
                'items' => [
                    ['deskripsi' => 'Biaya Langganan Maintenance Jaringan Pipa Sewer RS', 'qty' => 1, 'harga' => 12500000],
                    ['deskripsi' => 'Hydro Jetting Pembersihan Saluran Grease Trap Utama', 'qty' => 1, 'harga' => 3500000],
                ],
                'discount' => 10, // 10%
                'ppn' => 11, // 11%
                'pph' => 2, // 2%
            ],
            [
                'invoice_number' => "INV-KMT-1002-{$year}",
                'business_unit_id' => $unitJabodetabek->id,
                'client_id' => $client2->id,
                'kategori_invoice' => 'kemitraan',
                'periode_kontrak' => 'Maintenance Semester 1 2026',
                'due_date' => Carbon::now()->subDays(5),
                'status' => 'paid',
                'cause_of_problem' => 'Grease trap clogging prevention and sanitization.',
                'technician_names' => 'Ahmad Fauzi, Heri Prasetyo',
                'warranty' => '6 Bulan',
                'notes' => 'Periodic outlet sewer contract billing.',
                'items' => [
                    ['deskripsi' => 'Sanitasi & Desinfeksi Jaringan Pipa F&B Outlet Plaza Indonesia', 'qty' => 1, 'harga' => 8500000],
                    ['deskripsi' => 'Pembersihan Rutin Kitchen Grease Trap & Pompa Sump Pit', 'qty' => 1, 'harga' => 2500000],
                ],
                'discount' => 0,
                'ppn' => 11,
                'pph' => 0,
            ],
            [
                'invoice_number' => "INV-KMT-1003-{$year}",
                'business_unit_id' => $unitBogor->id,
                'client_id' => $client3->id,
                'kategori_invoice' => 'kemitraan',
                'periode_kontrak' => 'Maintenance Triwulan II 2026',
                'due_date' => Carbon::now()->addDays(7),
                'status' => 'sent',
                'cause_of_problem' => 'Main block public toilet sewer blockages.',
                'technician_names' => 'Budi Santoso, Dwi Cahyo',
                'warranty' => '1 Bulan',
                'notes' => 'Government services contract billing.',
                'items' => [
                    ['deskripsi' => 'Pembersihan Pipa Saluran Air Kotor Toilet Publik Gedung A & B', 'qty' => 1, 'harga' => 6000000],
                ],
                'discount' => 5, // 5%
                'ppn' => 11,
                'pph' => 2,
            ],
            [
                'invoice_number' => "INV-KMT-1004-{$year}",
                'business_unit_id' => $unitBogor->id,
                'client_id' => $client1->id,
                'kategori_invoice' => 'kemitraan',
                'periode_kontrak' => 'Maintenance Triwulan I 2026',
                'due_date' => Carbon::now()->subDays(30),
                'status' => 'overdue',
                'cause_of_problem' => 'Basement sump pit pump failure maintenance.',
                'technician_names' => 'Rahmat Hidayat',
                'warranty' => '2 Bulan',
                'notes' => 'Emergency sump pit pump service contract.',
                'items' => [
                    ['deskripsi' => 'Servis & Kalibrasi Pompa Sump Pit Basement RS', 'qty' => 1, 'harga' => 4500000],
                ],
                'discount' => 0,
                'ppn' => 0,
                'pph' => 2,
            ],
        ];
 
        // 3. Insert Invoices, Items, Payments, & Receipts
        foreach ($invoicesData as $data) {
            DB::transaction(function() use ($data) {
                // Calculate financial metrics
                $subtotal = 0;
                foreach ($data['items'] as $item) {
                    $subtotal += $item['qty'] * $item['harga'];
                }
 
                $discountVal = $data['discount'] > 100 ? $data['discount'] : ($subtotal * ($data['discount'] / 100));
                $dpp = $subtotal - $discountVal;
                $ppnVal = $data['ppn'] > 100 ? $data['ppn'] : ($dpp * ($data['ppn'] / 100));
                $pphVal = $data['pph'] > 100 ? $data['pph'] : ($dpp * ($data['pph'] / 100));
                $total = $dpp + $ppnVal + $pphVal;
 
                $invoice = Invoice::create([
                    'invoice_number' => $data['invoice_number'],
                    'business_unit_id' => $data['business_unit_id'],
                    'client_id' => $data['client_id'],
                    'subtotal' => $subtotal,
                    'discount' => round($discountVal, 2),
                    'ppn' => round($ppnVal, 2),
                    'pph' => round($pphVal, 2),
                    'total' => round($total, 2),
                    'status' => $data['status'],
                    'kategori_invoice' => $data['kategori_invoice'],
                    'periode_kontrak' => $data['periode_kontrak'],
                    'due_date' => $data['due_date'],
                    'cause_of_problem' => $data['cause_of_problem'],
                    'technician_names' => $data['technician_names'],
                    'warranty' => $data['warranty'],
                    'notes' => $data['notes'],
                    'created_by' => 1, // Assume first user (Admin)
                    'created_at' => Carbon::now()->subDays(10),
                    'updated_at' => Carbon::now()->subDays(10),
                ]);
 
                // Insert items
                foreach ($data['items'] as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'deskripsi' => $item['deskripsi'],
                        'qty' => $item['qty'],
                        'harga' => $item['harga'],
                        'total' => $item['qty'] * $item['harga'],
                    ]);
                }
 
                // If paid, create payment and receipt records
                if ($data['status'] === 'paid') {
                    $payment = Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'payment_date' => Carbon::now()->subDays(8),
                        'payment_method' => 'Transfer Bank',
                        'reference_number' => 'REF-' . rand(100000, 999999),
                        'notes' => 'Pelunasan Invoice Kemitraan',
                    ]);
 
                    Receipt::create([
                        'receipt_number' => str_replace('INV', 'KWT', $invoice->invoice_number),
                        'invoice_id' => $invoice->id,
                        'amount_received' => $invoice->total,
                        'payment_date' => Carbon::now()->subDays(8),
                    ]);
                }
            });
        }
    }
}
