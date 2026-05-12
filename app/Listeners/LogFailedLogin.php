<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogFailedLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'unknown';
        $ip = request()->ip();

        // Create security log
        \App\Models\SecurityLog::create([
            'user_id' => $event->user ? $event->user->id : null,
            'activity' => "Failed login attempt for: {$email}",
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'is_suspicious' => true,
        ]);

        // Check for consecutive failures
        $failureCount = \App\Models\SecurityLog::where('ip_address', $ip)
            ->where('activity', 'like', "Failed login attempt for: {$email}")
            ->where('created_at', '>', now()->subMinutes(10))
            ->count();

        if ($failureCount >= 3) {
            $admins = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\SecurityAlertNotification(
                    "High-Risk Login Failure",
                    "Account {$email} has failed login 3+ times from IP: {$ip}. Possible brute-force detected."
                ));
            }
        }
    }
}
