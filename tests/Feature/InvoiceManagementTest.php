<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_invoice_with_optional_financials_static_bank_and_structured_warranty(): void
    {
        $user = User::factory()->create();
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Test Client',
            'nama_perusahaan' => 'Test Company',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->post(route('invoices.store'), [
            'invoice_number' => 'JNJ-INV-99999',
            'client_id' => $client->id,
            'tanggal_invoice' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'tax_percent' => '', // optional, empty string
            'discount_percent' => '5', // 5%
            'warranty_value' => '3',
            'warranty_unit' => 'Bulan',
            'items' => [
                [
                    'deskripsi' => 'Standard Rooter Cleaning',
                    'qty' => 2,
                    'harga' => 250000,
                ]
            ],
            'terms_condition' => 'Net 7',
        ]);

        $response->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => 'JNJ-INV-99999',
            'client_id' => $client->id,
            'tax_percent' => 0, // empty tax casts to 0
            'discount_percent' => 5,
            'warranty' => '3 Bulan',
            'notes_internal' => null, // internal memo is removed/null
            'bank_account_info' => "Bank: Bank Central Asia (BCA)\nAcc No: 6281873404\nName: Wibowo Pratikno",
            'subtotal' => 500000,
            'total' => 475000, // 500000 - 5% discount (25000)
        ]);
    }

    public function test_can_update_invoice_and_enforce_static_bank_details(): void
    {
        $user = User::factory()->create();
        $client = Client::create([
            'kode_client' => 'CL-002',
            'nama_client' => 'Another Client',
            'status' => 'aktif',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'JNJ-INV-88888',
            'client_id' => $client->id,
            'tanggal_invoice' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'sent',
            'subtotal' => 100000,
            'tax_percent' => 11,
            'discount_percent' => 0,
            'total' => 111000,
            'bank_account_info' => 'Old Bank Info',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->put(route('invoices.update', $invoice), [
            'client_id' => $client->id,
            'tanggal_invoice' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'paid',
            'tax_percent' => '10',
            'discount_percent' => '',
            'warranty_value' => '1',
            'warranty_unit' => 'Tahun',
            'items' => [
                [
                    'deskripsi' => 'CCTV Pipe Inspection',
                    'qty' => 1,
                    'harga' => 300000,
                ]
            ],
            'terms_condition' => 'Paid terms',
        ]);

        $response->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
            'tax_percent' => 10,
            'discount_percent' => 0,
            'warranty' => '1 Tahun',
            'bank_account_info' => "Bank: Bank Central Asia (BCA)\nAcc No: 6281873404\nName: Wibowo Pratikno",
            'subtotal' => 300000,
            'total' => 330000, // 300000 + 10% tax (30000)
        ]);
    }
}
