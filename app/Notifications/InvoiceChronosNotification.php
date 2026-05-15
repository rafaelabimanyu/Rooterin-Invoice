<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceChronosNotification extends Notification
{
    use Queueable;

    protected $invoice;
    protected $type;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice, string $type, string $message)
    {
        $this->invoice = $invoice;
        $this->type = $type; // 'reminder', 'due_today', 'overdue'
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $icon = 'bell';
        $color = 'indigo';

        if ($this->type === 'overdue') {
            $icon = 'alert-octagon';
            $color = 'rose';
        } elseif ($this->type === 'due_today') {
            $icon = 'clock';
            $color = 'amber';
        }

        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'title' => 'Chronos Alert: ' . strtoupper($this->type),
            'message' => $this->message,
            'type' => $this->type,
            'icon' => $icon,
            'color' => $color,
            'action_url' => route('invoices.show', $this->invoice->id),
            'action_label' => 'View Invoice',
        ];
    }
}
