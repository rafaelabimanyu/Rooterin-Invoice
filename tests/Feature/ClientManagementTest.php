<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_client_with_custom_type_and_sector(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post(route('clients.store'), [
            'kode_client' => 'CL-TST-01',
            'client_type' => 'other',
            'custom_client_type' => 'Koperasi',
            'industry_sector' => 'other',
            'custom_industry_sector' => 'Pertanian',
            'nama_client' => 'Budi Santoso',
            'nama_perusahaan' => 'Koperasi Tani Makmur',
            'email' => 'budi@makmur.com',
            'no_hp' => '08123456789',
            'npwp' => '12.345.678.9-012.000',
            'alamat' => 'Jl. Tani Makmur No. 12',
            'kota' => 'Malang',
            'provinsi' => 'Jawa Timur',
            'catatan' => 'Internal note',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('clients.index'));
        
        $this->assertDatabaseHas('clients', [
            'kode_client' => 'CL-TST-01',
            'client_type' => 'Koperasi',
            'industry_sector' => 'Pertanian',
            'nama_client' => 'Budi Santoso',
            'nama_perusahaan' => 'Koperasi Tani Makmur',
        ]);
    }

    public function test_authenticated_user_can_update_client_with_standard_and_custom_types(): void
    {
        $user = User::factory()->create();
        $client = Client::create([
            'kode_client' => 'CL-TST-02',
            'client_type' => 'individual',
            'industry_sector' => 'general',
            'nama_client' => 'Ahmad',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('clients.update', $client), [
            'client_type' => 'other',
            'custom_client_type' => 'Yayasan',
            'industry_sector' => 'tech',
            'nama_client' => 'Ahmad Baru',
            'status' => 'nonaktif',
        ]);

        $response->assertRedirect(route('clients.index'));

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'client_type' => 'Yayasan',
            'industry_sector' => 'tech',
            'nama_client' => 'Ahmad Baru',
            'status' => 'nonaktif',
        ]);
    }

    public function test_authenticated_user_can_view_client_details_page_without_sql_errors(): void
    {
        $user = User::factory()->create();
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-TST-03',
            'client_type' => 'individual',
            'industry_sector' => 'general',
            'nama_client' => 'Dessy',
            'status' => 'aktif',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-001',
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'subtotal' => 100000,
            'total' => 100000,
            'status' => 'paid',
        ]);

        $invoice->receipt()->create([
            'receipt_number' => 'REC-001',
            'amount_received' => 100000,
            'payment_date' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('clients.show', $client));

        $response->assertStatus(200);
        $response->assertSee('REC-001');
    }
}
