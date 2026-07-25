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
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = $request->email;
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

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Help request deployed.']);
        }

        return redirect()->route('password.request')->with('status', 'Manual identity verification request has been logged. Please contact your administrator.');
    }
}
