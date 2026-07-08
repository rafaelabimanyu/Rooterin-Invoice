<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_invoice_with_optional_financials_static_bank_and_structured_warranty(): void
    {
        $user = User::factory()->create();
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Test Client',
            'nama_perusahaan' => 'Test Company',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->post(route('invoices.store'), [
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'discount' => '25000',
            'ppn' => '0',
            'pph' => '0',
            'items' => [
                [
                    'deskripsi' => 'Standard Rooter Cleaning',
                    'qty' => 2,
                    'harga' => 250000,
                ]
            ],
            'notes' => 'Some notes',
        ]);

        $response->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'discount' => 25000,
            'ppn' => 0,
            'pph' => 0,
            'subtotal' => 500000,
            'total' => 475000, // 500000 - 25000 discount
        ]);
    }

    public function test_can_update_invoice_and_enforce_static_bank_details(): void
    {
        $user = User::factory()->create();
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-002',
            'nama_client' => 'Another Client',
            'status' => 'aktif',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-5003-' . date('Y'),
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'due_date' => now()->addDays(7),
            'status' => 'sent',
            'subtotal' => 100000,
            'discount' => 0,
            'ppn' => 11000,
            'pph' => 0,
            'total' => 111000,
        ]);

        $response = $this->actingAs($user)->put(route('invoices.update', $invoice), [
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'paid',
            'discount' => '0',
            'ppn' => '30000',
            'pph' => '0',
            'items' => [
                [
                    'deskripsi' => 'CCTV Pipe Inspection',
                    'qty' => 1,
                    'harga' => 300000,
                ]
            ],
            'notes' => 'Paid terms',
        ]);

        $response->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
            'discount' => 0,
            'ppn' => 30000,
            'pph' => 0,
            'subtotal' => 300000,
            'total' => 330000, // 300000 + 30000 ppn
        ]);

        $this->assertDatabaseHas('receipts', [
            'invoice_id' => $invoice->id,
            'amount_received' => 330000,
        ]);
    }
}
