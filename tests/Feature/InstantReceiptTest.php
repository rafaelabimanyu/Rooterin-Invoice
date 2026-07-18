<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\BusinessUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstantReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_create_instant_receipt_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('receipts.create_instant'));

        $response->assertStatus(200);
        $response->assertViewIs('receipts.create_instant_receipt');
        $response->assertSee('Buat Kwitansi Instan');
    }

    public function test_can_store_instant_receipt_and_receive_pdf(): void
    {
        $user = User::factory()->create();
        
        $businessUnit = BusinessUnit::create([
            'name' => 'Rooterin Eco Plumbing',
            'slug' => 'rooterin-eco-plumbing',
            'is_active' => true,
        ]);

        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Instant Client',
            'nama_perusahaan' => 'Instant Corp',
            'client_type' => 'corporate',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->post(route('receipts.store_instant'), [
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'discount' => 10, // 10%
            'ppn' => 11, // 11%
            'pph' => 2, // 2%
            'technician_names' => 'John Doe, Jane Smith',
            'cause_of_problem' => 'Main pipe clog',
            'notes' => 'Completed task successfully',
            'warranty_value' => 3,
            'warranty_unit' => 'Bulan',
            'items' => [
                [
                    'deskripsi' => 'Emergency Rooter Service',
                    'qty' => 1,
                    'harga' => 1000000,
                ]
            ],
        ]);

        // Assert response is a PDF download
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        // Check Invoice exists and has status = paid and due_date = today
        $invoice = Invoice::first();
        $this->assertNotNull($invoice);
        $this->assertEquals('paid', $invoice->status);
        $this->assertEquals(now()->toDateString(), $invoice->due_date->toDateString());
        $this->assertEquals('John Doe, Jane Smith', $invoice->technician_names);
        $this->assertEquals('Main pipe clog', $invoice->cause_of_problem);
        $this->assertEquals('3 Bulan', $invoice->warranty);
        
        // Net subtotal: 1,000,000. Discount 10%: 100,000. DPP: 900,000.
        // PPN 11% of 900,000 = 99,000. PPh 2% of 900,000 = 18,000.
        // Total = 900,000 + 99,000 + 18,000 = 1,017,000.
        $this->assertEquals(1000000, $invoice->subtotal);
        $this->assertEquals(100000, $invoice->discount);
        $this->assertEquals(99000, $invoice->ppn);
        $this->assertEquals(18000, $invoice->pph);
        $this->assertEquals(1017000, $invoice->total);

        // Check Receipt exists and is linked
        $receipt = Receipt::first();
        $this->assertNotNull($receipt);
        $this->assertEquals($invoice->id, $receipt->invoice_id);
        $this->assertEquals(1017000, $receipt->amount_received);
    }
}
