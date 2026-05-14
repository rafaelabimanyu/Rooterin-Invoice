<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NavbarNotification extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        if ($user) {
            $dbNotifications = $user->notifications()->latest()->take(10)->get();
            $this->unreadCount = $user->unreadNotifications()->count();

            if ($dbNotifications->isEmpty()) {
                $logs = \App\Models\ActivityLog::latest()->take(5)->get();
                $securityLogs = \App\Models\SecurityLog::latest()->take(5)->get();
                
                $fakeNotifications = collect();
                
                foreach ($securityLogs as $log) {
                    $fakeNotifications->push((object)[
                        'id' => 'sec-'.$log->id,
                        'read_at' => null,
                        'created_at' => $log->created_at,
                        'data' => [
                            'title' => 'Security Alert',
                            'message' => $log->activity . ' from IP ' . $log->ip_address,
                            'type' => $log->is_suspicious ? 'critical' : 'security',
                            'action_url' => route('security.center'),
                            'action_label' => 'Review'
                        ]
                    ]);
                }
                
                foreach ($logs as $log) {
                    $fakeNotifications->push((object)[
                        'id' => 'act-'.$log->id,
                        'read_at' => null,
                        'created_at' => $log->created_at,
                        'data' => [
                            'title' => 'System Activity',
                            'message' => $log->description,
                            'type' => 'system'
                        ]
                    ]);
                }
                
                $this->notifications = $fakeNotifications->sortByDesc('created_at')->take(10);
                $this->unreadCount = $this->notifications->count();
            } else {
                $this->notifications = $dbNotifications;
            }
        }
    }

    public function markAsRead($id)
    {
        if (str_starts_with($id, 'sec-')) {
            return redirect()->route('security.center');
        }
        if (str_starts_with($id, 'act-')) {
            return;
        }

        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect if action_url exists
            if (isset($notification->data['action_url'])) {
                return redirect($notification->data['action_url']);
            }

            // Default redirect for security alerts to Intelligence Center
            if (isset($notification->data['type']) && $notification->data['type'] === 'security') {
                return redirect()->route('intelligence.index');
            }
            
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
        $this->dispatch('notify', ['message' => 'All notifications cleared.', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.navbar-notification');
    }
}
