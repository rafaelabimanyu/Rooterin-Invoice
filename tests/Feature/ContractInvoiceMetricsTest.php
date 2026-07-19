<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\BusinessUnit;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractInvoiceMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_reports_calculate_correct_revenue_split(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Test Corporate Client',
            'status' => 'aktif'
        ]);

        // 1. Regular Invoice (Paid)
        $regularInvoice = Invoice::create([
            'invoice_number' => 'INV-REG-101',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->toDateString(),
            'subtotal' => 1000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 1000000,
            'status' => 'paid',
            'kategori_invoice' => 'reguler',
        ]);

        Payment::create([
            'invoice_id' => $regularInvoice->id,
            'amount' => 1000000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transfer Bank',
        ]);

        // 2. Partnership Invoice (Paid)
        $partnershipInvoice = Invoice::create([
            'invoice_number' => 'INV-KMT-101',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->toDateString(),
            'subtotal' => 2000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 2000000,
            'status' => 'paid',
            'kategori_invoice' => 'kemitraan',
            'periode_kontrak' => 'Contract 2026',
        ]);

        Payment::create([
            'invoice_id' => $partnershipInvoice->id,
            'amount' => 2000000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transfer Bank',
        ]);

        // Access Dashboard
        $response = $this->actingAs($owner)->get(route('dashboard'));
        $response->assertStatus(200);

        $this->assertEquals(1000000, $response->viewData('regulerRevenue'));
        $this->assertEquals(2000000, $response->viewData('kemitraanRevenue'));
        $this->assertEquals(33, $response->viewData('regulerPercentage'));
        $this->assertEquals(67, $response->viewData('kemitraanPercentage'));

        // Access Reports
        $response = $this->actingAs($owner)->get(route('reports.index', [
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->endOfMonth()->toDateString(),
        ]));
        $response->assertStatus(200);

        $this->assertEquals(1000000, $response->viewData('regulerBilled'));
        $this->assertEquals(2000000, $response->viewData('kemitraanBilled'));
        $this->assertEquals(33, $response->viewData('regulerBilledPercentage'));
        $this->assertEquals(67, $response->viewData('kemitraanBilledPercentage'));

        $this->assertEquals(1000000, $response->viewData('regulerCollected'));
        $this->assertEquals(2000000, $response->viewData('kemitraanCollected'));
        $this->assertEquals(33, $response->viewData('regulerCollectedPercentage'));
        $this->assertEquals(67, $response->viewData('kemitraanCollectedPercentage'));
    }
}
