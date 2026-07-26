<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;

class BackupService
{
    /**
     * Generate database SQL dump and wrap it in a zip archive.
     *
     * @param bool $isAuto
     * @return string Path to the generated ZIP file
     */
    public function generateBackup(bool $isAuto = false): string
    {
        $dbName = config('database.connections.mysql.database');
        $timestamp = date('Y_m_d_Hi');
        
        $sqlFilename = $isAuto 
            ? "jnj_auto_backup_{$timestamp}.sql"
            : "jnj_backup_{$timestamp}.sql";
            
        $zipFilename = str_replace('.sql', '.zip', $sqlFilename);

        // Ensure directories exist
        $baseDir = storage_path('app/backups');
        $subDir = $isAuto ? 'automated' : 'manual';
        $targetDirectory = "{$baseDir}/{$subDir}";
        
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $zipPath = "{$targetDirectory}/{$zipFilename}";

        $dbDriver = DB::getDriverName();
        $isSqlite = $dbDriver === 'sqlite';

        // 1. Generate SQL content
        $sqlDump = "-- J&J Group Database Dump\n";
        $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sqlDump .= "-- Database Connection: {$dbDriver}\n\n";

        if ($isSqlite) {
            $sqlDump .= "PRAGMA foreign_keys = OFF;\n\n";
            $tables = array_map('current', DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"));
        } else {
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            $tables = array_map('current', DB::select('SHOW TABLES'));
        }

        foreach ($tables as $table) {
            // Get structure
            if ($isSqlite) {
                $createResult = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name = ?", [$table]);
                $createSql = $createResult[0]->sql ?? null;
            } else {
                $createResult = DB::select("SHOW CREATE TABLE `{$table}`");
                $createSql = $createResult[0]->{'Create Table'} ?? $createResult[0]->{'Create View'} ?? null;
            }

            if ($createSql) {
                $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sqlDump .= $createSql . ";\n\n";
            }

            // Get data
            $rows = DB::table($table)->get();
            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $rowArray = (array)$row;
                    $keys = array_map(fn($key) => "`{$key}`", array_keys($rowArray));
                    
                    $values = array_map(function($value) {
                        if (is_null($value)) {
                            return 'NULL';
                        }
                        return DB::getPdo()->quote($value);
                    }, array_values($rowArray));

                    $sqlDump .= "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
                $sqlDump .= "\n";
            }
        }

        if ($isSqlite) {
            $sqlDump .= "PRAGMA foreign_keys = ON;\n";
        } else {
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";
        }

        // 2. Compress into ZIP using native ZipArchive
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFromString($sqlFilename, $sqlDump);
            $zip->close();
        } else {
            throw new \Exception("Failed to create ZIP archive at: {$zipPath}");
        }

        return $zipPath;
    }

    /**
     * Rotate automated backups, keeping only files from the last 7 days.
     *
     * @return int Number of deleted backup files
     */
    public function rotateBackups(): int
    {
        $directory = storage_path('app/backups/automated');
        if (!file_exists($directory)) {
            return 0;
        }

        $files = glob($directory . '/*.zip');
        $deletedCount = 0;
        $cutoffTime = Carbon::now()->subDays(7)->timestamp;

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                if (unlink($file)) {
                    $deletedCount++;
                }
            }
        }

        return $deletedCount;
    }

    /**
     * Generate a ZIP archive of job documentation attachments.
     *
     * @param bool $isAuto
     * @param string|null $startDate
     * @param string|null $endDate
     * @return string Path to the generated ZIP file
     * @throws \Exception
     */
    public function generateDocsBackup(bool $isAuto = false, ?string $startDate = null, ?string $endDate = null): string
    {
        $timestamp = $isAuto ? date('Y_m_d_Hi') : date('d-m-Y_H-i');
        
        $zipFilename = $isAuto 
            ? "jnj_auto_docs_backup_{$timestamp}.zip"
            : "Dokumentasi_Pekerjaan_{$timestamp}.zip";

        // Ensure directories exist
        $baseDir = storage_path('app/backups/docs');
        $subDir = $isAuto ? 'automated' : 'manual';
        $targetDirectory = "{$baseDir}/{$subDir}";
        
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $zipPath = "{$targetDirectory}/{$zipFilename}";

        // Get attachments
        $query = \App\Models\InvoiceAttachment::query();

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            $query->where(function($q) use ($start, $end) {
                // 1. Attachment uploaded during the period
                $q->whereBetween('created_at', [$start, $end])
                  // 2. Or the invoice was created during the period
                  ->orWhereHas('invoice', function($invQuery) use ($start, $end) {
                      $invQuery->whereBetween('created_at', [$start, $end])
                               // 3. Or the associated receipt payment_date was during the period
                               ->orWhereHas('receipt', function($rcptQuery) use ($start, $end) {
                                   $rcptQuery->whereBetween('payment_date', [$start, $end]);
                               });
                  });
            });
        }

        $attachments = $query->with(['invoice.receipt'])->get();

        if ($attachments->isEmpty() && !$isAuto) {
            throw new \Exception(app()->getLocale() == 'en' ? "No job documentation attachments found for the specified period." : "Tidak ada foto dokumentasi ditemukan untuk periode tersebut.");
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $addedFiles = 0;
            $filenameCounts = [];

            foreach ($attachments as $attachment) {
                $filePath = $attachment->file_path;
                // Look in the 'public' storage directory
                $fullPath = storage_path('app/public/' . $filePath);

                if (file_exists($fullPath)) {
                    $invoice = $attachment->invoice;
                    $receipt = $invoice ? $invoice->receipt : null;
                    
                    if ($receipt) {
                        $prefixNumber = $receipt->receipt_number;
                    } elseif ($invoice) {
                        $prefixNumber = $invoice->invoice_number;
                    } else {
                        $prefixNumber = 'DOC';
                    }

                    // Sanitize prefix number for filename
                    $safePrefixNumber = preg_replace('/[^A-Za-z0-9_\-]/', '_', $prefixNumber);
                    
                    // Format upload timestamp (created_at of the attachment)
                    $uploadedAt = $attachment->created_at 
                        ? Carbon::parse($attachment->created_at)->format('d-m-Y_H-i') 
                        : date('d-m-Y_H-i');
                    
                    // Get original file extension
                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                    
                    // Base name without extension
                    $baseName = $safePrefixNumber . '_' . $uploadedAt;
                    
                    // Check for duplicate names in the ZIP
                    if (isset($filenameCounts[$baseName])) {
                        $filenameCounts[$baseName]++;
                        $fileNameInZip = $baseName . '_' . $filenameCounts[$baseName] . '.' . $extension;
                    } else {
                        $filenameCounts[$baseName] = 1;
                        $fileNameInZip = $baseName . '.' . $extension;
                    }

                    $zip->addFile($fullPath, $fileNameInZip);
                    $addedFiles++;
                } else {
                    Log::warning("Backup job documentation: physical file not found at " . $fullPath);
                }
            }

            $zip->close();

            if ($addedFiles === 0 && !$isAuto) {
                // Cleanup the empty ZIP
                @unlink($zipPath);
                throw new \Exception(app()->getLocale() == 'en' ? "None of the physical files found on the storage path." : "Tidak ada file fisik yang ditemukan di path penyimpanan.");
            }
        } else {
            throw new \Exception("Failed to create ZIP archive at: {$zipPath}");
        }

        return $zipPath;
    }

    /**
     * Rotate automated document backups, keeping only files from the last 7 days.
     *
     * @return int Number of deleted backup files
     */
    public function rotateDocsBackups(): int
    {
        $directory = storage_path('app/backups/docs/automated');
        if (!file_exists($directory)) {
            return 0;
        }

        $files = glob($directory . '/*.zip');
        $deletedCount = 0;
        $cutoffTime = Carbon::now()->subDays(7)->timestamp;

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                if (unlink($file)) {
                    $deletedCount++;
                }
            }
        }

        return $deletedCount;
    }
}
