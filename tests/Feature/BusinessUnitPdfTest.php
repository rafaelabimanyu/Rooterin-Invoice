<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BusinessUnit;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessUnitPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_pdf_export()
    {
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya Web',
            'slug' => 'jaya-web',
            'is_active' => true,
        ]);

        $response = $this->get(route('business-units.pdf', $businessUnit));

        $response->assertRedirect(route('login'));
    }

    public function test_staff_role_cannot_access_pdf_export()
    {
        $user = User::factory()->create(['role' => 'staff']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya Web',
            'slug' => 'jaya-web',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('business-units.pdf', $businessUnit));

        $response->assertStatus(403);
    }

    public function test_owner_role_can_download_business_unit_pdf_report()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya Web',
            'slug' => 'jaya-web',
            'is_active' => true,
        ]);

        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Client A',
            'status' => 'aktif',
        ]);

        Invoice::create([
            'invoice_number' => 'INV-2026-001',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->addDays(30),
            'subtotal' => 5000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 5000000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($user)->get(route('business-units.pdf', $businessUnit));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('attachment; filename=Laporan_Jaya_Web_', $response->headers->get('content-disposition'));
    }
}
