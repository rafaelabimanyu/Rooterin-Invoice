<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
use App\Models\Receipt;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffSecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;
    private User $otherStaff;
    private BusinessUnit $businessUnit;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staff = User::factory()->create([
            'role' => 'staff',
            'is_active' => 1,
        ]);

        $this->otherStaff = User::factory()->create([
            'role' => 'staff',
            'is_active' => 1,
        ]);

        $this->businessUnit = BusinessUnit::create([
            'name' => 'Security Test Unit',
            'slug' => 'security-test-unit',
            'is_active' => true,
        ]);

        $this->client = Client::create([
            'kode_client' => 'CL-SEC-01',
            'nama_client' => 'Security Client',
            'nama_perusahaan' => 'PT Security Perusahaan',
            'status' => 'aktif',
        ]);
    }

    public function test_staff_cannot_update_receipt_belonging_to_other_users_invoice(): void
    {
        // Invoice created by another staff member
        $invoice = Invoice::create([
            'invoice_number' => 'INV-SEC-001',
            'business_unit_id' => $this->businessUnit->id,
            'client_id' => $this->client->id,
            'subtotal' => 100000,
            'total' => 100000,
            'status' => 'paid',
            'created_by' => $this->otherStaff->id,
            'created_at' => now(),
        ]);

        $invoice->items()->create([
            'deskripsi' => 'Test Item',
            'qty' => 1,
            'harga' => 100000,
            'total' => 100000,
        ]);

        $receipt = Receipt::create([
            'receipt_number' => 'REC-SEC-001',
            'invoice_id' => $invoice->id,
            'amount_received' => 100000,
            'payment_date' => now(),
        ]);

        $this->actingAs($this->staff);

        $response = $this->put(route('receipts.update', $receipt), [
            'payment_date' => now()->toDateString(),
            'items' => [
                ['deskripsi' => 'Modified Item', 'qty' => 1, 'harga' => 150000]
            ],
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
        ]);

        $response->assertStatus(403);
    }

    public function test_staff_cannot_update_receipt_for_own_invoice_older_than_24_hours(): void
    {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-SEC-002',
            'business_unit_id' => $this->businessUnit->id,
            'client_id' => $this->client->id,
            'subtotal' => 100000,
            'total' => 100000,
            'status' => 'paid',
            'created_by' => $this->staff->id,
        ]);
        $invoice->created_at = now()->subHours(25);
        $invoice->save();

        $invoice->items()->create([
            'deskripsi' => 'Test Item',
            'qty' => 1,
            'harga' => 100000,
            'total' => 100000,
        ]);

        $receipt = Receipt::create([
            'receipt_number' => 'REC-SEC-002',
            'invoice_id' => $invoice->id,
            'amount_received' => 100000,
            'payment_date' => now(),
        ]);

        $this->actingAs($this->staff);

        $response = $this->put(route('receipts.update', $receipt), [
            'payment_date' => now()->toDateString(),
            'items' => [
                ['deskripsi' => 'Modified Item', 'qty' => 1, 'harga' => 150000]
            ],
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
        ]);

        $response->assertStatus(403);
    }

    public function test_staff_cannot_record_payment_on_other_users_invoice(): void
    {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-SEC-003',
            'business_unit_id' => $this->businessUnit->id,
            'client_id' => $this->client->id,
            'subtotal' => 100000,
            'total' => 100000,
            'status' => 'sent',
            'created_by' => $this->otherStaff->id,
            'created_at' => now(),
        ]);

        $this->actingAs($this->staff);

        $response = $this->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'amount' => 50000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transfer Bank',
        ]);

        $response->assertStatus(403);
    }
    public function test_staff_cannot_record_payment_on_own_invoice_older_than_24_hours(): void
    {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-SEC-004',
            'business_unit_id' => $this->businessUnit->id,
            'client_id' => $this->client->id,
            'subtotal' => 100000,
            'total' => 100000,
            'status' => 'sent',
            'created_by' => $this->staff->id,
        ]);
        $invoice->created_at = now()->subHours(25);
        $invoice->save();

        $this->actingAs($this->staff);

        $response = $this->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'amount' => 50000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transfer Bank',
        ]);

        $response->assertStatus(403);
    }

    public function test_staff_cannot_access_settings_pages(): void
    {
        $this->actingAs($this->staff);

        // GET Settings
        $responseGet = $this->get(route('settings.index'));
        $responseGet->assertStatus(403);

        // POST Settings
        $responsePost = $this->post(route('settings.update'), [
            'app_name' => 'Malicious Modification'
        ]);
        $responsePost->assertStatus(403);
    }
}
