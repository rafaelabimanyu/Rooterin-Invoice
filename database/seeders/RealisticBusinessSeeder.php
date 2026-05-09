<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Quotation;
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

        // 2. Clients (Indonesian Companies)
        $clientNames = [
            'PT Maju Jaya Teknik', 'CV Sumber Makmur', 'PT Teknologi Nusantara', 
            'UD Mandiri Sejahtera', 'PT Global Solusi', 'Langgeng Jaya Konstruksi',
            'Tunas Muda Renovasi', 'PT Indah Graha', 'CV Bintang Artha', 'PT Karya Abadi'
        ];

        foreach ($clientNames as $name) {
            Client::create([
                'kode_client' => Client::generateCode(),
                'nama_client' => $faker->name,
                'nama_perusahaan' => $name,
                'email' => $faker->companyEmail,
                'no_hp' => '08' . $faker->numerify('##########'),
                'npwp' => $faker->numerify('##.###.###.#-###.###'),
                'alamat' => $faker->address,
                'kota' => $faker->city,
                'provinsi' => $faker->state,
                'status' => 'aktif',
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

        // 5. Quotations
        for ($i = 1; $i <= 10; $i++) {
            $client = $clients->random();
            $subtotal = $faker->numberBetween(10, 100) * 100000;
            $tax = $subtotal * 0.11;
            $total = $subtotal + $tax;

            Quotation::create([
                'quotation_number' => "ROOT-QUO-" . str_pad($i, 5, '0', STR_PAD_LEFT),
                'client_id' => $client->id,
                'tanggal_quotation' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
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
