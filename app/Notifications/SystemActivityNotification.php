<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemActivityNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $type;
    public $actionUrl;
    public $actionLabel;

    public function __construct(string $title, string $message, string $type = 'finance', string $actionUrl = null, string $actionLabel = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type; // 'finance', 'security', 'critical', 'reminder', 'due_today'
        $this->actionUrl = $actionUrl;
        $this->actionLabel = $actionLabel;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'action_url' => $this->actionUrl,
            'action_label' => $this->actionLabel,
        ];
    }
}
