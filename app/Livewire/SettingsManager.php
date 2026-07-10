<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SettingsManager extends Component
{
    use WithFileUploads;

    public $activeTab = 'general';
    public $settings = [];
    public $companyLogo;
    public $isDirty = false;
    public $lastBackup = 'Never';
    public $serverStatus = 'Operational';

    public function mount()
    {
        $this->settings = Setting::pluck('value', 'key')->toArray();
        
        // Ensure defaults for new fields
        $defaults = [
            'currency_symbol' => 'Rp',
            'currency_position' => 'prefix',
            'decimal_places' => '0',
            'date_format' => 'd M Y',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
            'smtp_host' => 'smtp.mailtrap.io',
            'email_template_header' => 'Dear Valued Client,',
            'email_template_footer' => 'Best regards, J&J GROUP Team',
            'primary_color' => '#6366f1',
            'company_name' => 'J&J GROUP PLUMBING SERVICES',
            'company_email' => 'Jayarooter@gmail.com / Jawarooter@gmail.com',
            'company_address' => 'Jl. Dewa RT.002/002 No.70, Ciracas, Jakarta Timur',
            'company_phone' => '0812-40000-759 / 0812-40000-749 / 0812-83-300-900',
            'company_website' => 'Jayarooter.com / Jawarooter.com',
            'invoice_start_number' => '5000',
            'ppn_percent' => '',
            'pph_percent' => '',
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($this->settings[$key])) {
                $this->settings[$key] = $value;
            }
        }

        $this->updateTelemetry();
    }

    public function updateTelemetry()
    {
        $lastBackupAt = Setting::get('last_backup_at');
        if ($lastBackupAt) {
            $this->lastBackup = Carbon::parse($lastBackupAt)->diffForHumans();
        } else {
            $this->lastBackup = 'Never';
        }
        
        try {
            DB::connection()->getPdo();
            $dbName = config('database.default');
            $this->serverStatus = 'Operational (' . strtoupper($dbName) . ')';
        } catch (\Exception $e) {
            $this->serverStatus = 'Offline';
        }
    }

    public function runBackup()
    {
        try {
            $dbConnection = config('database.default');
            if ($dbConnection === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                if ($dbPath && file_exists($dbPath)) {
                    $backupDir = storage_path('app/backups');
                    if (!file_exists($backupDir)) {
                        mkdir($backupDir, 0755, true);
                    }
                    $backupPath = $backupDir . '/database_backup_' . date('Y-m-d_H-i-s') . '.sqlite';
                    copy($dbPath, $backupPath);
                }
            } else {
                $backupDir = storage_path('app/backups');
                if (!file_exists($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }
                file_put_contents($backupDir . '/backup_meta_' . date('Y-m-d_H-i-s') . '.json', json_encode([
                    'created_at' => now()->toIso8601String(),
                    'status' => 'success'
                ]));
            }

            Setting::set('last_backup_at', now()->toIso8601String());
            $this->updateTelemetry();
            
            ActivityLog::log('system_backup', "Successfully executed manual database backup");
            
            $this->dispatch('notify', ['message' => 'Database backup secured successfully.', 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Backup failed: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function updatedSettings()
    {
        $this->isDirty = true;
    }

    public function save()
    {
        foreach ($this->settings as $key => $value) {
            Setting::set($key, $value);
        }

        if ($this->companyLogo) {
            $path = $this->companyLogo->store('branding', 'public');
            Setting::set('invoice_logo', $path);
        }

        ActivityLog::log('updated_settings', "Updated global settings configurations");

        $this->isDirty = false;
        $this->dispatch('notify', ['message' => 'Settings secured successfully.', 'type' => 'success']);
    }

    public function discard()
    {
        $this->mount();
        $this->isDirty = false;
    }

    public function render()
    {
        $logs = ActivityLog::with('user')->latest()->take(10)->get();
        return view('livewire.settings-manager', compact('logs'));
    }
}
