<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // Create Clients
        $clients = [
            [
                'kode_client' => 'CLI-0001',
                'nama_client' => 'Budi Santoso',
                'nama_perusahaan' => 'PT Maju Jaya',
                'email' => 'budi@majujaya.com',
                'no_hp' => '081234567890',
                'status' => 'aktif',
                'kota' => 'Jakarta Selatan',
            ],
            [
                'kode_client' => 'CLI-0002',
                'nama_client' => 'Siti Aminah',
                'nama_perusahaan' => 'CV Sejahtera',
                'email' => 'siti@sejahtera.id',
                'no_hp' => '089876543210',
                'status' => 'aktif',
                'kota' => 'Bandung',
            ],
            [
                'kode_client' => 'CLI-0003',
                'nama_client' => 'Andi Wijaya',
                'nama_perusahaan' => 'PT Indo Construction',
                'email' => 'andi@indocon.co.id',
                'no_hp' => '085512344321',
                'status' => 'aktif',
                'kota' => 'Surabaya',
            ],
        ];

        foreach ($clients as $cData) {
            $client = Client::create($cData);

            // Create 2 Invoices for each client
            for ($i = 1; $i <= 2; $i++) {
                $status = $i % 2 == 0 ? 'paid' : 'sent';
                $invoice = Invoice::create([
                    'invoice_number' => Invoice::generateNumber(),
                    'client_id' => $client->id,
                    'tanggal_invoice' => Carbon::now()->subDays(rand(1, 30)),
                    'due_date' => Carbon::now()->addDays(rand(1, 15)),
                    'status' => $status,
                    'tax_percent' => 11,
                    'discount_percent' => 5,
                    'created_by' => $user->id,
                ]);

                // Create Items
                $items = [
                    ['deskripsi' => 'Perbaikan Pipa Bocor', 'qty' => 1, 'harga' => 500000],
                    ['deskripsi' => 'Ganti Keran Dapur', 'qty' => 2, 'harga' => 150000],
                    ['deskripsi' => 'Biaya Jasa Teknisi', 'qty' => 1, 'harga' => 250000],
                ];

                $subtotal = 0;
                foreach ($items as $iData) {
                    $totalItem = $iData['qty'] * $iData['harga'];
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'deskripsi' => $iData['deskripsi'],
                        'qty' => $iData['qty'],
                        'harga' => $iData['harga'],
                        'total' => $totalItem,
                    ]);
                    $subtotal += $totalItem;
                }

                $tax = $subtotal * 0.11;
                $discount = $subtotal * 0.05;
                $invoice->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal + $tax - $discount,
                ]);
            }
        }
    }
}
