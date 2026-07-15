<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    protected BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display the database backup management page.
     */
    public function index()
    {
        abort_if(!in_array(auth()->user()->role, ['owner', 'admin']), 403, 'Unauthorized access.');

        $dbConnection = config('database.default');
        $dbHost = config("database.connections.{$dbConnection}.host");
        $dbPort = config("database.connections.{$dbConnection}.port");
        $dbName = config("database.connections.{$dbConnection}.database");

        $autoStatus = Setting::get('backup_auto_status', 'off');
        $autoFrequency = Setting::get('backup_auto_frequency', 'daily');
        $autoTime = Setting::get('backup_auto_time', '23:00');

        return view('admin.backup', compact(
            'dbConnection',
            'dbHost',
            'dbPort',
            'dbName',
            'autoStatus',
            'autoFrequency',
            'autoTime'
        ));
    }

    /**
     * Update backup schedule settings.
     */
    public function updateSettings(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['owner', 'admin']), 403, 'Unauthorized access.');

        $validated = $request->validate([
            'backup_auto_status' => 'required|in:on,off',
            'backup_auto_frequency' => 'required|in:daily,weekly',
            'backup_auto_time' => 'required|date_format:H:i',
        ]);

        Setting::set('backup_auto_status', $validated['backup_auto_status']);
        Setting::set('backup_auto_frequency', $validated['backup_auto_frequency']);
        Setting::set('backup_auto_time', $validated['backup_auto_time']);

        // Log this administrative action
        Log::info("Backup settings updated by " . auth()->user()->email, $validated);

        return redirect()->route('backup.index')->with('success', app()->getLocale() == 'en' ? 'Backup settings updated successfully.' : 'Pengaturan backup berhasil diperbarui.');
    }

    /**
     * Run manual export and download the backup ZIP.
     */
    public function export()
    {
        abort_if(!in_array(auth()->user()->role, ['owner', 'admin']), 403, 'Unauthorized access.');

        try {
            $zipPath = $this->backupService->generateBackup(false);
            
            // Log this administrative action
            Log::info("Manual database backup generated and downloaded by " . auth()->user()->email);

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error("Manual backup failure: " . $e->getMessage());
            return redirect()->route('backup.index')->with('error', app()->getLocale() == 'en' ? 'Backup generation failed: ' . $e->getMessage() : 'Pembuatan backup gagal: ' . $e->getMessage());
        }
    }
}
