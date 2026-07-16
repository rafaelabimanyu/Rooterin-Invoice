<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BusinessUnit;
use App\Models\Client;
use App\Models\Invoice;
use App\Services\BusinessUnitReportingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessUnitProfitSharingTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $ownerUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->ownerUser = User::factory()->create(['role' => 'owner']);
    }

    public function test_business_unit_can_be_created_with_fee_percentage()
    {
        $response = $this->actingAs($this->adminUser)->post(route('business-units.store'), [
            'name' => 'Jaya Sosmed',
            'description' => 'Sosmed management division',
            'is_active' => true,
            'fee_percentage' => 7.50,
        ]);

        $response->assertRedirect(route('business-units.index'));
        $this->assertDatabaseHas('business_units', [
            'name' => 'Jaya Sosmed',
            'fee_percentage' => 7.50,
        ]);
    }

    public function test_fee_percentage_defaults_to_zero_if_omitted()
    {
        $response = $this->actingAs($this->adminUser)->post(route('business-units.store'), [
            'name' => 'Jaya Creative',
            'description' => 'Creative assets division',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('business-units.index'));
        $this->assertDatabaseHas('business_units', [
            'name' => 'Jaya Creative',
            'fee_percentage' => 0.00,
        ]);
    }

    public function test_business_unit_fee_percentage_can_be_updated()
    {
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya Web',
            'slug' => 'jaya-web',
            'is_active' => true,
            'fee_percentage' => 5.00,
        ]);

        $response = $this->actingAs($this->adminUser)->put(route('business-units.update', $businessUnit), [
            'name' => 'Jaya Web Refactored',
            'is_active' => true,
            'fee_percentage' => 12.00,
        ]);

        $response->assertRedirect(route('business-units.index'));
        $this->assertDatabaseHas('business_units', [
            'id' => $businessUnit->id,
            'name' => 'Jaya Web Refactored',
            'fee_percentage' => 12.00,
        ]);
    }

    public function test_reporting_service_correctly_calculates_gross_fee_and_net_revenues()
    {
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya Web',
            'slug' => 'jaya-web',
            'is_active' => true,
            'fee_percentage' => 10.00, // 10% management fee
        ]);

        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Client A',
            'status' => 'aktif',
        ]);

        // Paid invoice (Gross Revenue: 10,000,000)
        $invoice = Invoice::create([
            'invoice_number' => 'INV-001',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->addDays(30),
            'subtotal' => 10000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 10000000,
            'status' => 'paid',
        ]);

        \App\Models\Payment::create([
            'invoice_id' => $invoice->id,
            'payment_date' => now(),
            'amount' => 10000000,
            'payment_method' => 'Transfer Bank',
            'reference_number' => 'TEST-PAY-1',
        ]);

        // Pending invoice (Not paid - should be excluded from Gross/Fee/Net)
        Invoice::create([
            'invoice_number' => 'INV-002',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->addDays(30),
            'subtotal' => 5000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 5000000,
            'status' => 'sent',
        ]);

        $service = app(BusinessUnitReportingService::class);
        $summary = $service->getBusinessUnitsSummary();

        $this->assertCount(1, $summary);
        $unitSummary = $summary->first();

        $this->assertEquals(10000000.00, $unitSummary->gross_revenue);
        $this->assertEquals(10.00, $unitSummary->fee_percentage);
        $this->assertEquals(1000000.00, $unitSummary->fee_nominal);
        $this->assertEquals(9000000.00, $unitSummary->net_revenue);
    }

    public function test_owner_can_access_business_unit_profitability_pages_without_errors()
    {
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya Web',
            'slug' => 'jaya-web',
            'is_active' => true,
            'fee_percentage' => 8.00,
        ]);

        // 1. Detail (Show) Page
        $response = $this->actingAs($this->ownerUser)->get(route('business-units.show', $businessUnit));
        $response->assertStatus(200);
        $response->assertSee('8,0%'); // Percentage badge

        // 2. Reports Index Page
        $response = $this->actingAs($this->ownerUser)->get(route('reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Profit-Sharing Unit Bisnis');
    }
}
