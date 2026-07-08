<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoiceCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_voice_command_requires_authentication(): void
    {
        $response = $this->postJson(route('ai-assistant.voice-command'), ['command' => 'Buka kalender']);
        $response->assertStatus(401);
    }

    public function test_voice_command_redirects_for_calendar_intent(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($user)->postJson(route('ai-assistant.voice-command'), [
            'command' => 'J&J GROUP, Buka halaman kalender sekarang'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('chronos.index'),
                'message' => 'Mengalihkan ke halaman Kalender Chronos...'
            ]);
    }

    public function test_voice_command_queries_largest_unpaid_invoice(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => Client::generateCode(),
            'nama_client' => 'Budi Santoso',
            'client_type' => 'individual',
            'industry_sector' => 'general',
            'email' => 'budi@example.com',
            'telepon' => '08123456789',
            'status' => 'aktif',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'JNJ-INV-9999',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->addDays(7),
            'status' => 'sent',
            'subtotal' => 50000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 50000000,
        ]);

        $response = $this->actingAs($user)->postJson(route('ai-assistant.voice-command'), [
            'command' => 'J&J GROUP, tolong carikan invoice terbesar yang belum dibayar bulan ini'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'title', 'message'])
            ->assertJsonFragment([
                'success' => true,
                'title' => 'Invoice Terbesar Belum Dibayar',
            ]);

        $this->assertStringContainsString('Budi Santoso', $response->json('message'));
        $this->assertStringContainsString('Rp 50.000.000', $response->json('message'));
    }

    public function test_voice_command_queries_total_arrears(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => Client::generateCode(),
            'nama_client' => 'Siti Aminah',
            'client_type' => 'individual',
            'industry_sector' => 'fnb',
            'email' => 'siti@example.com',
            'telepon' => '08123456789',
            'status' => 'aktif',
        ]);

        Invoice::create([
            'invoice_number' => 'JNJ-INV-1111',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->addDays(7),
            'status' => 'sent',
            'subtotal' => 83571900,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 83571900,
        ]);

        $response = $this->actingAs($user)->postJson(route('ai-assistant.voice-command'), [
            'command' => 'Berapa total tunggakan aktif minggu ini?'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'title', 'message'])
            ->assertJsonFragment([
                'success' => true,
                'title' => 'Total Tunggakan Aktif',
            ]);

        $this->assertStringContainsString('Rp 83.571.900', $response->json('message'));
    }
}
