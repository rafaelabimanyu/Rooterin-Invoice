<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class MigrateOldInvoiceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooterin:migrate-old-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasi data dari database lama (mysql_lama) ke database baru (default mysql)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('====================================================');
        $this->info('      MEMULAI PROSES MIGRASI DATA DATABASE LAMA     ');
        $this->info('====================================================');
        
        $targetDb = config('database.connections.mysql.database');
        $sourceDb = config('database.connections.mysql_lama.database', 'rooterin-invoice-lama');
        
        $this->comment("Database Sumber (Lama) : {$sourceDb}");
        $this->comment("Database Target (Baru) : {$targetDb}");

        // 1. Nonaktifkan foreign key checks di awal proses
        $this->warn("\n[1/3] Menonaktifkan Foreign Key Checks...");
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // 2. Eksekusi migrasi tabel demi tabel
            $this->info("\n[2/3] Memproses migrasi tabel...");
            
            // CONTOH MIGRASI TABEL: Activity Logs
            $this->migrateActivityLogs();

            // CONTOH MIGRASI TABEL LAIN (Anda bisa tambahkan method-method baru di bawah):
            // $this->migrateUsers();
            // $this->migrateClients();
            // $this->migrateInvoices();
            // $this->migratePayments();

            $this->info("\n====================================================");
            $this->info('      MIGRATION SELESAI DENGAN SUKSES!             ');
            $this->info('====================================================');
        } catch (Exception $e) {
            $this->error("\n====================================================");
            $this->error('      TERJADI ERROR SAAT MIGRASI!                  ');
            $this->error('Error: ' . $e->getMessage());
            $this->error('====================================================');
        } finally {
            // 3. Pastikan foreign key checks selalu diaktifkan kembali
            $this->warn("\n[3/3] Mengaktifkan kembali Foreign Key Checks...");
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        return Command::SUCCESS;
    }

    /**
     * Method Contoh Migrasi Tabel Activity Logs.
     * Silakan tiru pola ini untuk membuat migrasi tabel-tabel lainnya.
     */
    protected function migrateActivityLogs()
    {
        $sourceTable = 'activity_logs'; // Nama tabel di database lama
        $targetTable = 'activity_logs'; // Nama tabel di database baru
        $chunkSize = 500;              // Ukuran chunk (misalnya 100 atau 500 baris)

        $this->comment("\n-> Memulai migrasi tabel: {$sourceTable}...");

        try {
            // Dapatkan total baris untuk membuat progress bar
            $totalRecords = DB::connection('mysql_lama')->table($sourceTable)->count();
        } catch (Exception $e) {
            $this->error("   [ERROR] Tidak dapat mengakses tabel '{$sourceTable}' di koneksi 'mysql_lama'.");
            $this->error("   Detail error: " . $e->getMessage());
            $this->warn("   Pastikan koneksi 'mysql_lama' sudah ditambahkan di config/database.php dan .env");
            return;
        }

        if ($totalRecords === 0) {
            $this->info("   [INFO] Tabel '{$sourceTable}' kosong atau tidak memiliki data.");
            return;
        }

        // Tampilkan progress bar yang keren di terminal
        $bar = $this->output->createProgressBar($totalRecords);
        $bar->start();

        // Ambil data dengan method chunk() agar memori tidak jebol
        DB::connection('mysql_lama')
            ->table($sourceTable)
            ->orderBy('id') // Gunakan primary key (id) untuk ordering agar chunk berjalan konsisten
            ->chunk($chunkSize, function ($rows) use ($targetTable, $bar) {
                $mappedRows = [];

                foreach ($rows as $row) {
                    // =========================================================================
                    // AREA MAPPING KOLOM (SESUAIKAN DENGAN SKEMA ANDA)
                    // =========================================================================
                    // Di bawah ini adalah pemetaan fiktif/contoh dari tabel lama ke tabel baru.
                    // Kunci array (misalnya 'id', 'user_id') mewakili nama kolom di tabel BARU.
                    // Nilai (misalnya $row->id, $row->user_id) mewakili data/kolom dari tabel LAMA.
                    // =========================================================================
                    $mappedRows[] = [
                        'id'          => $row->id,
                        
                        // Menangani relasi user_id (opsional: pastikan datanya sinkron)
                        'user_id'     => $row->user_id,
                        
                        // Menyesuaikan kolom 'action'
                        'action'      => $row->action, 
                        
                        // Contoh kasus: kolom deskripsi di database lama bernama 'old_description' atau 'desc'
                        // tetapi di database baru bernama 'description'
                        'description' => $row->description ?? $row->desc ?? '', 
                        
                        // Kolom nullable atau polymophic
                        'model_type'  => $row->model_type ?? null,
                        'model_id'    => $row->model_id ?? null,
                        
                        // Kolom JSON
                        'properties'  => $row->properties ?? null,
                        
                        // Kolom alamat IP
                        'ip_address'  => $row->ip_address ?? null,
                        
                        // Timestamp default jika di tabel lama kosong
                        'created_at'  => $row->created_at ?? now(),
                        'updated_at'  => $row->updated_at ?? now(),
                    ];
                }

                // Simpan data chunk secara massal (bulk insert) ke database baru
                DB::table($targetTable)->insert($mappedRows);

                // Update progress bar di terminal
                $bar->advance(count($rows));
            });

        $bar->finish();
        $this->newLine();
        $this->info("   [SUCCESS] Selesai memigrasikan tabel '{$sourceTable}'. Total data: {$totalRecords} baris.");
    }
}
