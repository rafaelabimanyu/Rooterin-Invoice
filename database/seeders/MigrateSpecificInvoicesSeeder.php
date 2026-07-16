<?php

namespace Database\Seeders;

use App\Models\BusinessUnit;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceAttachment;
use App\Models\Receipt;
use App\Models\Payment;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MigrateSpecificInvoicesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Resolve Admin/Owner user for 'created_by' relation
        $adminUser = User::whereIn('role', ['owner', 'admin'])->first() ?? User::first();
        $createdBy = $adminUser ? $adminUser->id : 1;

        // 2. Ensure Business Units exist (checking by slug)
        $buJabodetabek = BusinessUnit::updateOrCreate(
            ['slug' => 'jaya-jabodetabek'],
            [
                'name' => 'JAYA-JABODETABEK',
                'fee_percentage' => 1.00,
                'is_active' => true,
            ]
        );

        $buBogor = BusinessUnit::updateOrCreate(
            ['slug' => 'jaya-bogor'],
            [
                'name' => 'JAYA-BOGOR',
                'fee_percentage' => 1.00,
                'is_active' => true,
            ]
        );

        // 3. Migrate Clients
        $clientRicky = Client::updateOrCreate(
            ['kode_client' => 'CLI-0020'],
            [
                'client_type' => 'perusahaan',
                'industry_sector' => 'general',
                'nama_client' => 'Bpk. Ricky',
                'nama_perusahaan' => 'Hoi Kee Hainanese Chicken Rice & Porridge Cengkareng',
                'no_hp' => '+62 812-9923-392',
                'alamat' => 'Jl. Taman Palem Lestari No.32 Blok A11, RT.3/RW.13, Cengkareng Bar., Kecamatan Cengkareng, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11710',
                'status' => 'aktif',
                'created_at' => '2026-07-16 00:57:59',
                'updated_at' => '2026-07-16 00:57:59',
            ]
        );

        $clientNabilla = Client::updateOrCreate(
            ['kode_client' => 'CLI-0021'],
            [
                'client_type' => 'perusahaan',
                'industry_sector' => 'general',
                'nama_client' => 'Ibu Nabilla',
                'nama_perusahaan' => 'Richeese Factory - Metropolitan Mall Cileungsi',
                'no_hp' => '+62 822-2104-1950',
                'alamat' => 'Mall Metropolitan, Cileungsi Kidul, Cileungsi, Bogor Regency, West Java 16820',
                'status' => 'aktif',
                'created_at' => '2026-07-16 03:52:55',
                'updated_at' => '2026-07-16 03:52:55',
            ]
        );

        // 4. Migrate Invoices
        $invoice17 = Invoice::updateOrCreate(
            ['invoice_number' => 'INV-5017-2026'],
            [
                'business_unit_id' => $buJabodetabek->id,
                'client_id' => $clientRicky->id,
                'subtotal' => 1000000.00,
                'discount' => 0.00,
                'ppn' => 0.00,
                'pph' => 0.00,
                'total' => 1000000.00,
                'status' => 'draft',
                'due_date' => '2026-07-16',
                'cause_of_problem' => 'Karena adanya Lemak',
                'notes' => 'Pekerjaan ini telah diverifikasi langsung di lokasi oleh teknisi kami menggunakan peralatan Ridgid.',
                'technician_names' => 'Bpk. Aris dan Bpk. Andri',
                'created_by' => $createdBy,
                'created_at' => '2026-07-16 01:04:55',
                'updated_at' => '2026-07-16 01:04:55',
            ]
        );

        $invoice18 = Invoice::updateOrCreate(
            ['invoice_number' => 'INV-5018-2026'],
            [
                'business_unit_id' => $buJabodetabek->id,
                'client_id' => $clientRicky->id,
                'subtotal' => 1000000.00,
                'discount' => 0.00,
                'ppn' => 0.00,
                'pph' => 0.00,
                'total' => 1000000.00,
                'status' => 'draft',
                'due_date' => '2026-07-16',
                'cause_of_problem' => 'Karena adanya Lemak',
                'notes' => 'Pekerjaan ini telah diverifikasi langsung di lokasi oleh teknisi kami menggunakan peralatan Ridgid.',
                'technician_names' => 'Bpk. Aris dan Bpk. Andri',
                'created_by' => $createdBy,
                'created_at' => '2026-07-16 01:04:57',
                'updated_at' => '2026-07-16 01:04:57',
            ]
        );

        $invoice19 = Invoice::updateOrCreate(
            ['invoice_number' => 'INV-5019-2026'],
            [
                'business_unit_id' => $buBogor->id,
                'client_id' => $clientNabilla->id,
                'subtotal' => 500000.00,
                'discount' => 0.00,
                'ppn' => 0.00,
                'pph' => 0.00,
                'total' => 500000.00,
                'status' => 'paid',
                'due_date' => '2026-07-16',
                'cause_of_problem' => 'Sisa tepung, sarung tangan, & masker',
                'notes' => 'Pekerjaan ini telah diverifikasi langsung di lokasi oleh teknisi kami menggunakan peralatan Ridgid.',
                'technician_names' => 'Bpk. Loly dan Bpk. Andri',
                'created_by' => $createdBy,
                'created_at' => '2026-07-16 03:57:24',
                'updated_at' => '2026-07-16 03:57:40',
            ]
        );

        // 5. Migrate Invoice Items (Clean & Recreate)
        InvoiceItem::whereIn('invoice_id', [$invoice17->id, $invoice18->id, $invoice19->id])->delete();

        InvoiceItem::create([
            'invoice_id' => $invoice17->id,
            'deskripsi' => 'All Cleaning Saluran',
            'qty' => 1.00,
            'harga' => 1000000.00,
            'total' => 1000000.00,
            'created_at' => '2026-07-16 01:04:55',
            'updated_at' => '2026-07-16 01:04:55',
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice18->id,
            'deskripsi' => 'All Cleaning Saluran',
            'qty' => 1.00,
            'harga' => 1000000.00,
            'total' => 1000000.00,
            'created_at' => '2026-07-16 01:04:57',
            'updated_at' => '2026-07-16 01:04:57',
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice19->id,
            'deskripsi' => 'Greeter Kitchen / Gutter',
            'qty' => 1.00,
            'harga' => 500000.00,
            'total' => 500000.00,
            'created_at' => '2026-07-16 03:57:40',
            'updated_at' => '2026-07-16 03:57:40',
        ]);

        // 6. Migrate Invoice Attachments (Clean & Recreate)
        InvoiceAttachment::whereIn('invoice_id', [$invoice17->id, $invoice18->id, $invoice19->id])->delete();

        // Attachments for INV-5017-2026
        $attachments17 = [
            'attachments/Cwo411cU0OJR74TYsqCisj4xCFCx0hF9W7Nt8JCz.jpg',
            'attachments/mOM3HfoWYFpbDQ1d4EuRrZ6eczAqhs7OzBIqKylu.jpg',
            'attachments/7kVrWqtTd5Wo9l8k2EstosqFi7FnVHb4pBmfrBQI.jpg',
            'attachments/TkCVIMptg0NZyWTwpcRkY28dIZY5dnyu0icwEqBy.jpg',
            'attachments/x9QhppcHmSfKLLa45WZbATdgWvqdHw8pVxvQ1Siu.jpg',
            'attachments/vrGPipm2nO8xBur5ReRaONzy3ZRvWb8K0q3ypYVe.jpg',
        ];
        foreach ($attachments17 as $path) {
            InvoiceAttachment::create([
                'invoice_id' => $invoice17->id,
                'file_path' => $path,
                'created_at' => '2026-07-16 01:04:55',
                'updated_at' => '2026-07-16 01:04:55',
            ]);
        }

        // Attachments for INV-5018-2026
        $attachments18 = [
            'attachments/KQ8rmpPPkd3q86tSgp51DfCMh8E0qYEONn0zvX18.jpg',
            'attachments/ftV1xYVZEK093mLPdc17HC0Fjity4UT3CR0SgLI3.jpg',
            'attachments/g3UUsazAgHsRH7RKwFUC7ggcrnkYNGW1IamLSLss.jpg',
            'attachments/tCrfjtkxsPlDm729sVRVPhFTc36hoBqihDerXHuo.jpg',
            'attachments/YWtOvwur1k1d3880SPyBuEXgLB7djPpVamdKp9Jm.jpg',
            'attachments/wHethcb8yLPn8lognJ2piP9hYeOkcqOEj3PDB0UW.jpg',
        ];
        foreach ($attachments18 as $path) {
            InvoiceAttachment::create([
                'invoice_id' => $invoice18->id,
                'file_path' => $path,
                'created_at' => '2026-07-16 01:04:57',
                'updated_at' => '2026-07-16 01:04:57',
            ]);
        }

        // Attachments for INV-5019-2026
        $attachments19 = [
            'attachments/olb9GUeHtPtSn1tEgxdm2S5W2MGLTWQDWtn1wqog.jpg',
            'attachments/U6JIWouwRAnH8xqO7EWqQyyvVZXm4YYailcfeXf1.jpg',
            'attachments/L6u16eRmbBG4wdmdKyhXhPCejWDk7h6jTH2UFqyJ.jpg',
        ];
        foreach ($attachments19 as $path) {
            InvoiceAttachment::create([
                'invoice_id' => $invoice19->id,
                'file_path' => $path,
                'created_at' => '2026-07-16 03:57:24',
                'updated_at' => '2026-07-16 03:57:24',
            ]);
        }

        // 7. Migrate Receipts
        Receipt::updateOrCreate(
            ['receipt_number' => 'KWT-5019-2026'],
            [
                'invoice_id' => $invoice19->id,
                'amount_received' => 500000.00,
                'payment_date' => '2026-07-16 03:57:40',
                'created_at' => '2026-07-16 03:57:40',
                'updated_at' => '2026-07-16 03:57:40',
            ]
        );

        // 8. Migrate Payments (ensuring metrics/dashboard consistency)
        Payment::updateOrCreate(
            [
                'invoice_id' => $invoice19->id,
                'amount' => 500000.00,
            ],
            [
                'payment_date' => '2026-07-16',
                'payment_method' => 'Transfer Bank',
                'reference_number' => 'AUTO-MIGRATED',
                'notes' => 'Auto-generated payment entry for migrated paid invoice',
                'created_at' => '2026-07-16 03:57:40',
                'updated_at' => '2026-07-16 03:57:40',
            ]
        );

        // 9. Migrate Activity Logs
        $logs = [
            [
                'action' => 'created_invoice',
                'description' => 'Issued new invoice #INV-5017-2026',
                'model_type' => 'App\\Models\\Invoice',
                'model_id' => $invoice17->id,
                'created_at' => '2026-07-16 01:04:55',
            ],
            [
                'action' => 'created_invoice',
                'description' => 'Issued new invoice #INV-5018-2026',
                'model_type' => 'App\\Models\\Invoice',
                'model_id' => $invoice18->id,
                'created_at' => '2026-07-16 01:04:57',
            ],
            [
                'action' => 'created_invoice',
                'description' => 'Issued new invoice #INV-5019-2026',
                'model_type' => 'App\\Models\\Invoice',
                'model_id' => $invoice19->id,
                'created_at' => '2026-07-16 03:57:25',
            ],
            [
                'action' => 'updated_invoice',
                'description' => 'Updated invoice #INV-5019-2026',
                'model_type' => 'App\\Models\\Invoice',
                'model_id' => $invoice19->id,
                'created_at' => '2026-07-16 03:57:40',
            ],
        ];

        foreach ($logs as $logData) {
            ActivityLog::updateOrCreate(
                [
                    'action' => $logData['action'],
                    'description' => $logData['description'],
                    'created_at' => $logData['created_at'],
                ],
                [
                    'user_id' => $createdBy,
                    'model_type' => $logData['model_type'],
                    'model_id' => $logData['model_id'],
                    'ip_address' => '182.253.251.43',
                    'updated_at' => $logData['created_at'],
                ]
            );
        }
    }
}
