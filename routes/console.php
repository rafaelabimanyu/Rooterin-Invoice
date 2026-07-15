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
    }
} catch (\Exception $e) {
    // Avoid blocking CLI operations during initial setup/migration phases
}
