<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_access_reports_page(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertViewHas('invoiceStats');
        $response->assertViewHas('paymentStats');
        $response->assertViewHas('totalOutstanding');
        $response->assertViewHas('recentInvoices');
    }

    public function test_admin_can_access_reports_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(200);
    }

    public function test_staff_cannot_access_reports_page(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(403);
    }

    public function test_reports_page_filters_data_correctly(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        
        $client1 = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Client One',
            'status' => 'aktif'
        ]);
        
        $client2 = Client::create([
            'kode_client' => 'CL-002',
            'nama_client' => 'Client Two',
            'status' => 'aktif'
        ]);

        // Invoice for client 1
        $invoice1 = Invoice::create([
            'invoice_number' => 'INV-C1-01',
            'client_id' => $client1->id,
            'created_by' => $owner->id,
            'tanggal_invoice' => '2026-05-10',
            'due_date' => '2026-06-10',
            'subtotal' => 1000000,
            'tax' => 0,
            'total' => 1000000,
            'status' => 'pending'
        ]);

        // Invoice for client 2
        $invoice2 = Invoice::create([
            'invoice_number' => 'INV-C2-01',
            'client_id' => $client2->id,
            'created_by' => $owner->id,
            'tanggal_invoice' => '2026-05-15',
            'due_date' => '2026-06-15',
            'subtotal' => 2000000,
            'tax' => 0,
            'total' => 2000000,
            'status' => 'paid'
        ]);

        // Access reports filtering by client 1
        $response = $this->actingAs($owner)->get(route('reports.index', [
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-31',
            'client_id' => $client1->id
        ]));

        $response->assertStatus(200);
        $invoiceStats = $response->viewData('invoiceStats');
        $this->assertEquals(1, $invoiceStats['total_count']);
        $this->assertEquals(1000000, $invoiceStats['total_value']);

        // Access reports filtering by client 2
        $response = $this->actingAs($owner)->get(route('reports.index', [
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-31',
            'client_id' => $client2->id
        ]));

        $response->assertStatus(200);
        $invoiceStats = $response->viewData('invoiceStats');
        $this->assertEquals(1, $invoiceStats['total_count']);
        $this->assertEquals(2000000, $invoiceStats['total_value']);
    }
}
