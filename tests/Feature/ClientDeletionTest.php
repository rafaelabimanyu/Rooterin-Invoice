<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\ClientManager;

class ClientDeletionTest extends TestCase
{
    use RefreshDatabase;

    private function createClient(): Client
    {
        return Client::create([
            'kode_client' => 'CL-' . uniqid(),
            'nama_client' => 'Test Client',
            'nama_perusahaan' => 'PT Test Perusahaan',
            'status' => 'aktif',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
        ]);
    }

    public function test_owner_can_delete_client_via_controller(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'is_active' => 1,
        ]);

        $client = $this->createClient();

        $response = $this->actingAs($owner)
            ->delete(route('clients.destroy', $client));

        $response->assertRedirect(route('clients.index'));
        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }

    public function test_admin_can_delete_client_via_controller(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => 1,
        ]);

        $client = $this->createClient();

        $response = $this->actingAs($admin)
            ->delete(route('clients.destroy', $client));

        $response->assertRedirect(route('clients.index'));
        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }

    public function test_staff_can_delete_client_via_controller(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'is_active' => 1,
        ]);

        $client = $this->createClient();

        $response = $this->actingAs($staff)
            ->delete(route('clients.destroy', $client));

        $response->assertRedirect(route('clients.index'));
        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }

    public function test_owner_can_delete_client_via_livewire(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'is_active' => 1,
        ]);

        $client = $this->createClient();

        $this->actingAs($owner);

        Livewire::test(ClientManager::class)
            ->call('delete', $client->id)
            ->assertDispatched('notify');

        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }

    public function test_staff_can_delete_client_via_livewire(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'is_active' => 1,
        ]);

        $client = $this->createClient();

        $this->actingAs($staff);

        Livewire::test(ClientManager::class)
            ->call('delete', $client->id)
            ->assertDispatched('notify');

        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }
}
