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

        $docAutoStatus = Setting::get('doc_backup_auto_status', 'off');
        $docAutoFrequency = Setting::get('doc_backup_auto_frequency', 'daily');
        $docAutoTime = Setting::get('doc_backup_auto_time', '01:00');

        return view('admin.backup', compact(
            'dbConnection',
            'dbHost',
            'dbPort',
            'dbName',
            'autoStatus',
            'autoFrequency',
            'autoTime',
            'docAutoStatus',
            'docAutoFrequency',
            'docAutoTime'
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

    /**
     * Update job documentation backup schedule settings.
     */
    public function updateDocSettings(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['owner', 'admin']), 403, 'Unauthorized access.');

        $validated = $request->validate([
            'doc_backup_auto_status' => 'required|in:on,off',
            'doc_backup_auto_frequency' => 'required|in:daily,weekly,monthly',
            'doc_backup_auto_time' => 'required|date_format:H:i',
        ]);

        Setting::set('doc_backup_auto_status', $validated['doc_backup_auto_status']);
        Setting::set('doc_backup_auto_frequency', $validated['doc_backup_auto_frequency']);
        Setting::set('doc_backup_auto_time', $validated['doc_backup_auto_time']);

        // Log administrative action
        Log::info("Job documentation backup settings updated by " . auth()->user()->email, $validated);

        return redirect()->route('backup.index')->with('success', app()->getLocale() == 'en' ? 'Job documentation backup settings updated successfully.' : 'Pengaturan cadangan dokumentasi berhasil diperbarui.');
    }

    /**
     * Run manual job documentation export and download ZIP.
     */
    public function exportDocs(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['owner', 'admin']), 403, 'Unauthorized access.');

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $zipPath = $this->backupService->generateDocsBackup(false, $validated['start_date'], $validated['end_date']);

            // Log administrative action
            Log::info("Manual job documentation backup generated and downloaded by " . auth()->user()->email . " for period: {$validated['start_date']} to {$validated['end_date']}");

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error("Manual job documentation backup failure: " . $e->getMessage());
            return redirect()->route('backup.index')->with('error', $e->getMessage());
        }
    }
}
