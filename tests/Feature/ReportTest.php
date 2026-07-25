<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
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

    public function test_staff_can_access_reports_page(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(200);
    }

    public function test_reports_page_filters_data_correctly(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        
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
        $invoice1 = new Invoice([
            'invoice_number' => 'INV-C1-01',
            'client_id' => $client1->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => '2026-06-10',
            'subtotal' => 1000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 1000000,
            'status' => 'pending'
        ]);
        $invoice1->created_at = '2026-05-10 12:00:00';
        $invoice1->save();

        // Invoice for client 2
        $invoice2 = new Invoice([
            'invoice_number' => 'INV-C2-01',
            'client_id' => $client2->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => '2026-06-15',
            'subtotal' => 2000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 2000000,
            'status' => 'paid'
        ]);
        $invoice2->created_at = '2026-05-15 12:00:00';
        $invoice2->save();

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
