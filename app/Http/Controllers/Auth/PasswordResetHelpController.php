<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordResetHelpController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $email = $request->email ?? 'unknown_operative';
        $ip = $request->ip();

        // Create security log
        \App\Models\SecurityLog::create([
            'activity' => "Manual Identity Verification Requested for: {$email}",
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'is_suspicious' => false,
        ]);

        // Notify Admins
        $admins = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SecurityAlertNotification(
                "Identity Verification Request",
                "Operative {$email} has requested manual password assistance from IP: {$ip}."
            ));
        }

        return response()->json(['message' => 'Help request deployed.']);
    }
}
