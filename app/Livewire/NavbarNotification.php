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
            $this->notifications = $user->notifications()->latest()->take(10)->get();
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function markAsRead($id)
    {
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
