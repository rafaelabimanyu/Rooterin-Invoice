<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\BusinessUnit;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $clients = Client::all();
        $businessUnits = BusinessUnit::all();
        $users = User::all();

        if ($clients->isEmpty() || $businessUnits->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Service Descriptions
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

        $statuses = ['draft', 'sent', 'pending', 'paid', 'overdue', 'cancelled'];
        
        // 1. Generate 30 diverse Invoices & Billing Lifecycle
        for ($i = 1; $i <= 30; $i++) {
            $client = $clients->random();
            $bu = $businessUnits->random();
            $creator = $users->random();
            
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

            // Assign technicians
            $technicians = $faker->randomElement([
                'Roni Wijaya', 
                'Muhammad Fikri', 
                'Roni Wijaya, Muhammad Fikri', 
                'Staff Lapangan J&J', 
                null
            ]);

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
                'technician_names' => $technicians,
                'created_by' => $creator->id,
                'notes' => 'Pekerjaan ini telah diverifikasi langsung di lokasi oleh teknisi kami menggunakan peralatan presisi tinggi, sesuai dengan standar kualitas J&J GROUP.',
                'created_at' => $date,
                'updated_at' => $date,
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

            // Generate Receipt for 'paid' status
            if ($status === 'paid') {
                Receipt::create([
                    'receipt_number' => "KWT-" . (5003 + $i) . "-2026",
                    'invoice_id' => $invoice->id,
                    'amount_received' => $total,
                    'payment_date' => $date->copy()->addDays($faker->numberBetween(1, 5)),
                ]);
            }
        }

        // 2. Generate 8 additional Paid Invoices and Receipts
        for ($i = 1; $i <= 8; $i++) {
            $client = $clients->random();
            $bu = $businessUnits->random();
            $creator = $users->random();
            
            $subtotal = $faker->numberBetween(10, 100) * 150000;
            $discount = 0;
            $ppn = $subtotal * 0.11;
            $pph = 0;
            $total = $subtotal + $ppn;

            $date = Carbon::now()->subDays($faker->numberBetween(1, 45));
            $dueDate = $date->copy()->addDays(30);

            $technicians = $faker->randomElement(['Roni Wijaya', 'Muhammad Fikri', 'Roni Wijaya, Muhammad Fikri', null]);

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
                'due_date' => $dueDate,
                'cause_of_problem' => null,
                'technician_names' => $technicians,
                'created_by' => $creator->id,
                'notes' => 'Pekerjaan ini telah diverifikasi langsung di lokasi oleh teknisi kami menggunakan peralatan presisi tinggi, sesuai dengan standar kualitas J&J GROUP.',
                'created_at' => $date,
                'updated_at' => $date,
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
                'payment_date' => $date->copy()->addDays($faker->numberBetween(1, 4)),
            ]);
        }
    }
}
