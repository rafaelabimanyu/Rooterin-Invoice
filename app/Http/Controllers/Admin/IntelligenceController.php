<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SecurityLog;

class IntelligenceController extends Controller
{
    /**
     * Display the Security Intelligence Center.
     */
    public function index()
    {
        $securityLogs = SecurityLog::latest()->paginate(20, ['*'], 'logs_page');
        $notifications = auth()->user()->notifications()->paginate(20, ['*'], 'notif_page');
        
        return view('admin.intelligence', compact('securityLogs', 'notifications'));
    }

    /**
     * Mark a notification as read and redirect if needed.
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        // If it's a security alert, we might want to redirect to logs
        if (isset($notification->data['type']) && $notification->data['type'] === 'security') {
            return redirect()->route('intelligence.index')->with('success', 'Security alert acknowledged.');
        }

        return back()->with('success', 'Notification marked as read.');
    }
}
