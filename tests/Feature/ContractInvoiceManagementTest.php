<?php
 
namespace Tests\Feature;
 
use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
 
class ContractInvoiceManagementTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_can_create_partnership_invoice_with_contract_period(): void
    {
        $user = User::factory()->create();
        $businessUnit = BusinessUnit::create([
            'name' => 'J&J Logistics',
            'slug' => 'jj-logistics',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-KMT-001',
            'nama_client' => 'Sinar Mas Group',
            'nama_perusahaan' => 'PT Sinar Mas',
            'client_type' => 'corporate',
            'status' => 'aktif',
        ]);
 
        $response = $this->actingAs($user)->post(route('contract-invoices.store'), [
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'periode_kontrak' => 'Juli 2026 - Juni 2027',
            'discount' => '10', // 10%
            'ppn' => '11', // 11%
            'pph' => '2', // 2%
            'items' => [
                [
                    'deskripsi' => 'Monthly Plumbing Maintenance (Contract Agreement)',
                    'qty' => 1,
                    'harga' => 5000000,
                ]
            ],
            'technician_names' => 'Ahmad, Budi',
            'cause_of_problem' => 'Routine Preventive Maintenance',
            'notes' => 'Contract Billing Cycle #1',
        ]);
 
        $response->assertRedirect(route('contract-invoices.index'));
 
        $this->assertDatabaseHas('invoices', [
            'client_id' => $client->id,
            'kategori_invoice' => 'kemitraan',
            'periode_kontrak' => 'Juli 2026 - Juni 2027',
            'subtotal' => 5000000,
            'discount' => 500000, // 10% of 5,000,000
            'ppn' => 495000, // 11% of (5M - 500k = 4.5M)
            'pph' => 90000, // 2% of (5M - 500k = 4.5M)
            'total' => 5085000, // 4.5M + 495k + 90k
            'technician_names' => 'Ahmad, Budi',
        ]);
    }
 
    public function test_can_update_partnership_invoice_and_maintain_kategori_flag(): void
    {
        $user = User::factory()->create();
        $businessUnit = BusinessUnit::create([
            'name' => 'J&J Logistics',
            'slug' => 'jj-logistics',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-KMT-002',
            'nama_client' => 'Indofood Sukses',
            'status' => 'aktif',
        ]);
 
        $invoice = Invoice::create([
            'invoice_number' => 'INV-KMT-0001',
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'kategori_invoice' => 'kemitraan',
            'periode_kontrak' => 'Jan - Des 2026',
            'subtotal' => 2000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 2000000,
            'status' => 'draft',
        ]);
 
        $response = $this->actingAs($user)->put(route('contract-invoices.update', $invoice), [
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'due_date' => now()->addDays(15)->format('Y-m-d'),
            'periode_kontrak' => 'Jan - Des 2026 (Revised)',
            'status' => 'sent',
            'items' => [
                [
                    'deskripsi' => 'Contract Pipe Servicing',
                    'qty' => 1,
                    'harga' => 2500000,
                ]
            ],
        ]);
 
        $response->assertRedirect(route('contract-invoices.index'));
 
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'kategori_invoice' => 'kemitraan',
            'periode_kontrak' => 'Jan - Des 2026 (Revised)',
            'subtotal' => 2500000,
            'total' => 2500000,
            'status' => 'sent',
        ]);
    }
 
    public function test_isolation_boundary_cannot_view_regular_invoice_on_contract_routes(): void
    {
        $user = User::factory()->create();
        $businessUnit = BusinessUnit::create(['name' => 'Unit A', 'slug' => 'unit-a']);
        $client = Client::create(['kode_client' => 'CL-01', 'nama_client' => 'Reg Client', 'status' => 'aktif']);
 
        $regularInvoice = Invoice::create([
            'invoice_number' => 'INV-REG-9999',
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'kategori_invoice' => 'reguler', // or null
            'subtotal' => 100000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 100000,
        ]);
 
        $response = $this->actingAs($user)->get(route('contract-invoices.show', $regularInvoice));
        $response->assertStatus(404);
    }
 
    public function test_isolation_boundary_cannot_view_contract_invoice_on_regular_routes(): void
    {
        $user = User::factory()->create();
        $businessUnit = BusinessUnit::create(['name' => 'Unit A', 'slug' => 'unit-a']);
        $client = Client::create(['kode_client' => 'CL-01', 'nama_client' => 'Reg Client', 'status' => 'aktif']);
 
        $contractInvoice = Invoice::create([
            'invoice_number' => 'INV-KMT-8888',
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'kategori_invoice' => 'kemitraan',
            'subtotal' => 100000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 100000,
        ]);
 
        $response = $this->actingAs($user)->get(route('invoices.show', $contractInvoice));
        $response->assertStatus(404);
    }
 
    public function test_can_download_contract_invoice_pdf_template(): void
    {
        $user = User::factory()->create();
        $businessUnit = BusinessUnit::create(['name' => 'Unit A', 'slug' => 'unit-a']);
        $client = Client::create(['kode_client' => 'CL-01', 'nama_client' => 'Reg Client', 'status' => 'aktif']);
 
        $invoice = Invoice::create([
            'invoice_number' => 'INV-KMT-1234',
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'kategori_invoice' => 'kemitraan',
            'periode_kontrak' => 'Q3 2026',
            'subtotal' => 100000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 100000,
        ]);
 
        $response = $this->actingAs($user)->get(route('contract-invoices.pdf', $invoice));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
