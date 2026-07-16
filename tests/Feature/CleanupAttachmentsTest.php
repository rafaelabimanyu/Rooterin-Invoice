<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CleanupAttachmentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cleanup_command_removes_orphaned_attachments_and_keeps_valid_ones()
    {
        // 1. Setup fake disk
        Storage::fake('public');

        // Create directory
        Storage::disk('public')->makeDirectory('attachments');

        // 2. Setup database entities
        $bu = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Test Client',
            'status' => 'aktif',
        ]);
        $invoice = Invoice::create([
            'invoice_number' => 'INV-5003-' . date('Y'),
            'business_unit_id' => $bu->id,
            'client_id' => $client->id,
            'due_date' => now()->addDays(7),
            'status' => 'sent',
            'subtotal' => 100000,
            'discount' => 0,
            'ppn' => 11000,
            'pph' => 0,
            'total' => 111000,
        ]);

        // 3. Create active attachment record in database and mock file on disk
        $activeFilePath = 'attachments/active_attachment_1.jpg';
        InvoiceAttachment::create([
            'invoice_id' => $invoice->id,
            'file_path' => $activeFilePath,
        ]);
        Storage::disk('public')->put($activeFilePath, 'dummy content');

        // 4. Create orphan file on disk (not in database)
        $orphanFilePath = 'attachments/orphan_attachment_2.jpg';
        Storage::disk('public')->put($orphanFilePath, 'dummy content');

        // 5. Assert files exist initially
        Storage::disk('public')->assertExists($activeFilePath);
        Storage::disk('public')->assertExists($orphanFilePath);

        // 6. Run the Artisan command
        $this->artisan('storage:cleanup-attachments')
            ->expectsOutput('Scanning attachments directory...')
            ->expectsOutput('Menghapus 1 file sampah...')
            ->expectsOutput('Proses selesai. 1 file valid dipertahankan.')
            ->assertExitCode(0);

        // 7. Assert orphan file was deleted, and active file was kept
        Storage::disk('public')->assertExists($activeFilePath);
        Storage::disk('public')->assertMissing($orphanFilePath);
    }
}
