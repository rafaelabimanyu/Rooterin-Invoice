<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\BusinessUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrashManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_admin_can_access_trash_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('trash.index'));

        $response->assertStatus(200);
    }

    public function test_staff_cannot_access_trash_page(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->get(route('trash.index'));

        $response->assertStatus(403);
    }

    public function test_invoice_can_be_soft_deleted_and_restored(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Test Client',
            'nama_perusahaan' => 'Test Company',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'status' => 'aktif',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'JNJ-INV-9999',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->addDays(7),
            'status' => 'sent',
            'subtotal' => 100000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 100000,
        ]);

        // Soft delete
        $response = $this->actingAs($user)->delete(route('invoices.destroy', $invoice->id));
        $response->assertRedirect(route('invoices.index'));

        $this->assertSoftDeleted('invoices', [
            'id' => $invoice->id,
        ]);

        // Verify in trash
        $this->assertTrue(Invoice::onlyTrashed()->where('id', $invoice->id)->exists());

        // Restore
        $response = $this->actingAs($user)->post(route('trash.invoices.restore', $invoice->id));
        $response->assertRedirect(route('trash.index'));

        $this->assertNotSoftDeleted('invoices', [
            'id' => $invoice->id,
        ]);
    }

    public function test_invoice_can_be_permanently_deleted_with_attachments_purged(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'admin']);
        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Test Client',
            'nama_perusahaan' => 'Test Company',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'status' => 'aktif',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'JNJ-INV-9998',
            'client_id' => $client->id,
            'business_unit_id' => $businessUnit->id,
            'due_date' => now()->addDays(7),
            'status' => 'sent',
            'subtotal' => 100000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 100000,
        ]);

        // Create an attachment file
        $file_path = 'attachments/test_file.pdf';
        Storage::disk('public')->put($file_path, 'dummy content');

        $attachment = $invoice->attachments()->create([
            'file_name' => 'test_file.pdf',
            'file_path' => $file_path,
        ]);

        $invoice->delete(); // Soft delete first

        // Purge
        $response = $this->actingAs($user)->delete(route('trash.invoices.purge', $invoice->id));
        $response->assertRedirect(route('trash.index'));

        // Assert record is permanently deleted
        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);
        $this->assertDatabaseMissing('invoice_attachments', [
            'id' => $attachment->id,
        ]);

        // Assert file is purged from disk
        Storage::disk('public')->assertMissing($file_path);
    }
}
