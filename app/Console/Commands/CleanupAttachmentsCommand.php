<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\InvoiceAttachment;

class CleanupAttachmentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:cleanup-attachments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused (orphan) attachment files from the public disk storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning attachments directory...');

        if (!Storage::disk('public')->exists('attachments')) {
            $this->warn('Folder "attachments" tidak ditemukan di storage public.');
            return;
        }

        // Get all files from disk
        $files = Storage::disk('public')->files('attachments');
        
        // Get all database attachment paths flipped for O(1) lookup
        $dbPaths = InvoiceAttachment::pluck('file_path')->flip()->toArray();

        $deletedCount = 0;
        $keptCount = 0;

        foreach ($files as $file) {
            // Skip directory paths if any
            if (is_dir(Storage::disk('public')->path($file))) {
                continue;
            }

            if (!isset($dbPaths[$file])) {
                Storage::disk('public')->delete($file);
                $deletedCount++;
            } else {
                $keptCount++;
            }
        }

        $this->info("Menghapus {$deletedCount} file sampah...");
        $this->info("Proses selesai. {$keptCount} file valid dipertahankan.");
    }
}
