<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\OwnerKpi;

class OwnerKpiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_owner_kpi_page(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($user)->get(route('owner.kpi'));

        $response->assertStatus(200);
        $response->assertSeeLivewire(OwnerKpi::class);
    }

    public function test_owner_kpi_livewire_can_open_revenue_modal(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        Livewire::actingAs($user)
            ->test(OwnerKpi::class)
            ->call('openModal', 'revenue')
            ->assertDispatched('slide-over-loading-start')
            ->assertDispatched('open-slide-over')
            ->assertDispatched('init-lucide-icons');
    }

    public function test_owner_kpi_livewire_can_open_risks_modal(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        Livewire::actingAs($user)
            ->test(OwnerKpi::class)
            ->call('openModal', 'risks')
            ->assertDispatched('slide-over-loading-start')
            ->assertDispatched('open-slide-over')
            ->assertDispatched('init-lucide-icons');
    }

    public function test_owner_kpi_livewire_can_open_loyalty_modal(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        Livewire::actingAs($user)
            ->test(OwnerKpi::class)
            ->call('openModal', 'loyalty')
            ->assertDispatched('slide-over-loading-start')
            ->assertDispatched('open-slide-over')
            ->assertDispatched('init-lucide-icons');
    }

    public function test_owner_kpi_livewire_can_open_prime_asset_modal(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        Livewire::actingAs($user)
            ->test(OwnerKpi::class)
            ->call('openModal', 'prime-asset')
            ->assertDispatched('slide-over-loading-start')
            ->assertDispatched('open-slide-over')
            ->assertDispatched('init-lucide-icons');
    }

    public function test_owner_kpi_livewire_can_open_client_modal(): void
    {
        $user = User::factory()->create(['role' => 'owner']);
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Client Test',
            'status' => 'aktif',
        ]);

        Livewire::actingAs($user)
            ->test(OwnerKpi::class)
            ->call('openModal', 'client', $client->id)
            ->assertDispatched('slide-over-loading-start')
            ->assertDispatched('open-slide-over')
            ->assertDispatched('init-lucide-icons');
    }

    public function test_owner_kpi_livewire_can_open_payment_modal(): void
    {
        $user = User::factory()->create(['role' => 'owner']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-002',
            'nama_client' => 'Client 2',
            'status' => 'aktif'
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-001',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->addDays(30),
            'subtotal' => 1000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 1000000,
            'status' => 'pending'
        ]);

        $payment = \App\Models\Receipt::create([
            'receipt_number' => 'KWT-001',
            'invoice_id' => $invoice->id,
            'amount_received' => 500000,
            'payment_date' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(OwnerKpi::class)
            ->call('openModal', 'payment', $payment->id)
            ->assertDispatched('slide-over-loading-start')
            ->assertDispatched('open-slide-over')
            ->assertDispatched('init-lucide-icons');
    }
}
