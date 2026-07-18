<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdaptiveTransactionTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $businessUnit;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->businessUnit = BusinessUnit::create([
            'name' => 'Rooterin Plumbing Jakarta',
            'slug' => 'rooterin-plumbing-jakarta',
            'is_active' => true,
        ]);
        $this->client = Client::create([
            'kode_client' => 'CL-TX-001',
            'nama_client' => 'Budi Sudarsono',
            'nama_perusahaan' => 'PSSI',
            'status' => 'aktif',
        ]);
    }

    public function test_can_create_invoice_mode_transaction(): void
    {
        $response = $this->actingAs($this->user)->post(route('transactions.store'), [
            'mode' => 'invoice',
            'business_unit_id' => $this->businessUnit->id,
            'client_id' => $this->client->id,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'unpaid',
            'discount' => 50000,
            'ppn_percentage' => 11,
            'pph_percentage' => 2,
            'technician_names' => 'John Doe, Mark Smith',
            'cause_of_clog' => 'Grease buildup and solid debris',
            'warranty_info' => '30 Hari',
            'documentation_links' => ['assets/img1.jpg', 'assets/img2.jpg'],
            'items' => [
                [
                    'description' => 'Drain Clog Service',
                    'qty' => 1,
                    'price' => 500000,
                ],
                [
                    'description' => 'Pipe Clean Up',
                    'qty' => 2,
                    'price' => 100000,
                ]
            ],
            'notes' => 'Invoice terms standard',
        ]);

        $response->assertRedirect();
        
        // Assert transaction was created
        $this->assertDatabaseHas('transactions', [
            'mode' => 'invoice',
            'status' => 'unpaid',
            'business_unit_id' => $this->businessUnit->id,
            'client_id' => $this->client->id,
            'subtotal' => 700000,
            'discount' => 50000,
            'total' => 708500, // (700000-50000) = 650000. PPN = 71500, PPh = 13000. 650000 + 71500 - 13000 = 708500
        ]);

        $transaction = Transaction::first();

        // Assert project details were created with correct data
        $this->assertDatabaseHas('project_details', [
            'transaction_id' => $transaction->id,
            'technician_names' => 'John Doe, Mark Smith',
            'cause_of_clog' => 'Grease buildup and solid debris',
            'warranty_info' => '30 Hari',
            'ppn_percentage' => 11.00,
            'pph_percentage' => 2.00,
            'ppn_amount' => 71500.00,
            'pph_amount' => 13000.00,
        ]);

        // Verify documentation links array casting
        $this->assertEquals(['assets/img1.jpg', 'assets/img2.jpg'], $transaction->projectDetail->documentation_links);

        // Assert line items
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transaction->id,
            'description' => 'Drain Clog Service',
            'qty' => 1,
            'price' => 500000,
        ]);
    }

    public function test_can_create_receipt_mode_transaction_and_auto_sets_paid_and_today(): void
    {
        $response = $this->actingAs($this->user)->post(route('transactions.store'), [
            'mode' => 'receipt',
            'business_unit_id' => $this->businessUnit->id,
            'client_id' => $this->client->id,
            'payment_method' => 'Transfer',
            'discount' => 0,
            'ppn_percentage' => 0,
            'pph_percentage' => 0,
            'technician_names' => 'Ahmad',
            'cause_of_clog' => 'Hair clog',
            'warranty_info' => '7 Hari',
            'items' => [
                [
                    'description' => 'Instant service',
                    'qty' => 1,
                    'price' => 200000,
                ]
            ],
        ]);

        // Since it's receipt mode, it returns a PDF download response
        $response->assertHeader('content-type', 'application/pdf');

        // Assert transaction was created and automatically set to paid with today's date
        $transaction = Transaction::first();
        $this->assertNotNull($transaction);
        $this->assertEquals('receipt', $transaction->mode);
        $this->assertEquals('paid', $transaction->status);
        $this->assertEquals(now()->toDateString(), $transaction->payment_date->toDateString());
        $this->assertEquals('Transfer', $transaction->payment_method);
        $this->assertNull($transaction->due_date);
        $this->assertEquals(200000, $transaction->total);

        // Assert project details were saved even though it's receipt presentation mode
        $this->assertDatabaseHas('project_details', [
            'transaction_id' => $transaction->id,
            'technician_names' => 'Ahmad',
            'cause_of_clog' => 'Hair clog',
            'warranty_info' => '7 Hari',
        ]);
    }

    public function test_technical_details_fallback_to_defaults_when_empty(): void
    {
        $response = $this->actingAs($this->user)->post(route('transactions.store'), [
            'mode' => 'invoice',
            'business_unit_id' => $this->businessUnit->id,
            'client_id' => $this->client->id,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'unpaid',
            'items' => [
                [
                    'description' => 'Generic Fix',
                    'qty' => 1,
                    'price' => 100000,
                ]
            ],
            // Omitting technical fields
        ]);

        $response->assertRedirect();

        $transaction = Transaction::first();

        // Assert fallback defaults are correctly populated for analytics
        $this->assertDatabaseHas('project_details', [
            'transaction_id' => $transaction->id,
            'technician_names' => 'Umum',
            'cause_of_clog' => '-',
            'warranty_info' => 'Tidak Ada Garansi',
        ]);
    }
}
