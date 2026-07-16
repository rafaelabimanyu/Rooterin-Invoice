<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('chronos:check-due-dates')->daily();

Schedule::call(function () {
    app(\App\Services\AutoReportService::class)->generateDailyReport();
})->dailyAt('08:00');

Schedule::call(function () {
    app(\App\Services\AutoReportService::class)->checkUrgentOverdueInvoices();
})->hourly();

// 1. Register Automated Database Backup Command
Artisan::command('db:backup-auto', function (\App\Services\BackupService $backupService) {
    $this->info('Starting automated database backup...');
    Log::info('Artisan scheduled backup starting...');
    try {
        $zipPath = $backupService->generateBackup(true);
        $this->info("Backup generated successfully at: {$zipPath}");
        Log::info("Automated database backup successfully created: " . basename($zipPath));
        
        $deletedCount = $backupService->rotateBackups();
        if ($deletedCount > 0) {
            $this->info("Cleaned up {$deletedCount} old backup files (older than 7 days).");
            Log::info("Backup rotation completed. Removed {$deletedCount} old backups.");
        }
    } catch (\Exception $e) {
        $this->error("Automated backup failed: " . $e->getMessage());
        Log::error("Automated backup failure: " . $e->getMessage());
    }
})->purpose('Run the automated database backup process and rotate old backups');

// 3. Register Automated Job Documentation Backup Command
Artisan::command('docs:backup-auto', function (\App\Services\BackupService $backupService) {
    $this->info('Starting automated job documentation backup...');
    Log::info('Artisan scheduled job documentation backup starting...');
    try {
        $zipPath = $backupService->generateDocsBackup(true);
        $this->info("Job documentation backup generated successfully at: {$zipPath}");
        Log::info("Automated job documentation backup successfully created: " . basename($zipPath));
        
        $deletedCount = $backupService->rotateDocsBackups();
        if ($deletedCount > 0) {
            $this->info("Cleaned up {$deletedCount} old document backup files (older than 7 days).");
            Log::info("Job documentation backup rotation completed. Removed {$deletedCount} old backups.");
        }
    } catch (\Exception $e) {
        $this->error("Automated job documentation backup failed: " . $e->getMessage());
        Log::error("Automated job documentation backup failure: " . $e->getMessage());
    }
})->purpose('Run the automated job documentation backup process and rotate old backups');

// 2. Dynamic Automated Backup Scheduler (Guarded against early migration state)
try {
    if (class_exists(\App\Models\Setting::class) && \Illuminate\Support\Facades\Schema::hasTable('settings')) {
        $backupAutoStatus = \App\Models\Setting::get('backup_auto_status', 'off');
        if ($backupAutoStatus === 'on') {
            $backupAutoFrequency = \App\Models\Setting::get('backup_auto_frequency', 'daily');
            $backupAutoTime = \App\Models\Setting::get('backup_auto_time', '23:00');
            
            $scheduledCommand = Schedule::command('db:backup-auto');
            
            if ($backupAutoFrequency === 'weekly') {
                $scheduledCommand->weeklyOn(1, $backupAutoTime); // Weekly on Monday
            } else {
                $scheduledCommand->dailyAt($backupAutoTime);
            }
        }

        // Dynamic Automated Job Documentation Backup Scheduler
        $docBackupAutoStatus = \App\Models\Setting::get('doc_backup_auto_status', 'off');
        if ($docBackupAutoStatus === 'on') {
            $docBackupAutoFrequency = \App\Models\Setting::get('doc_backup_auto_frequency', 'daily');
            $docBackupAutoTime = \App\Models\Setting::get('doc_backup_auto_time', '01:00');
            
            $docScheduledCommand = Schedule::command('docs:backup-auto');
            
            if ($docBackupAutoFrequency === 'weekly') {
                $docScheduledCommand->weeklyOn(1, $docBackupAutoTime); // Weekly on Monday
            } elseif ($docBackupAutoFrequency === 'monthly') {
                $docScheduledCommand->monthlyOn(1, $docBackupAutoTime); // Monthly on the 1st
            } else {
                $docScheduledCommand->dailyAt($docBackupAutoTime);
            }
        }
    }
} catch (\Exception $e) {
    // Avoid blocking CLI operations during initial setup/migration phases
}
