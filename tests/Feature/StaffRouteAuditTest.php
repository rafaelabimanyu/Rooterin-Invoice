<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
use App\Models\Receipt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffRouteAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_accessible_routes(): void
    {
        // 1. Create a staff user
        $staff = User::factory()->create([
            'role' => 'staff',
            'is_active' => 1,
        ]);

        // 2. Create prerequisite data manually (no factories exist for these)
        $businessUnit = BusinessUnit::create([
            'name' => 'Test Business Unit',
            'slug' => 'test-business-unit',
            'is_active' => true,
        ]);

        $client = Client::create([
            'kode_client' => 'CL-TEST-01',
            'nama_client' => 'Test Client',
            'nama_perusahaan' => 'PT Test Perusahaan',
            'status' => 'aktif',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-TEST-001',
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'subtotal' => 100000,
            'total' => 100000,
            'status' => 'sent',
            'created_by' => $staff->id,
            'created_at' => now(),
        ]);

        // Also create an invoice item as it might be rendered
        $invoice->items()->create([
            'deskripsi' => 'Test Item',
            'qty' => 1,
            'harga' => 100000,
            'total' => 100000,
        ]);

        $receipt = Receipt::create([
            'receipt_number' => 'REC-TEST-001',
            'invoice_id' => $invoice->id,
            'amount_received' => 100000,
            'payment_date' => now(),
        ]);

        $this->actingAs($staff);

        // 3. Visit and audit each route
        $routes = [
            '/dashboard' => 200,
            '/guide' => 200,
            '/privacy-policy' => 200,
            '/terms-of-service' => 200,
            '/help-center' => 200,
            '/clients' => 200,
            '/clients/create' => 200,
            "/clients/{$client->id}" => 200,
            "/clients/{$client->id}/edit" => 302,
            '/invoices' => 200,
            '/invoices/create' => 200,
            "/invoices/{$invoice->id}" => 200,
            "/invoices/{$invoice->id}/edit" => 200,
            '/receipts' => 200,
            "/receipts/{$receipt->id}" => 200,
            '/profile' => 200,
            '/intelligence' => 200,
            "/invoices/{$invoice->id}/pdf" => 200,
            "/receipts/{$receipt->id}/pdf" => 200,
        ];

        foreach ($routes as $url => $expectedStatus) {
            $response = $this->get($url);
            $this->assertEquals(
                $expectedStatus,
                $response->status(),
                "Route {$url} failed with status {$response->status()}. Response: " . substr($response->getContent(), 0, 500)
            );
        }
    }
}
