<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'properties',
        'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $description, $model = null, $properties = null)
    {
        $log = self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'properties' => $properties,
            'ip_address' => request()->ip(),
        ]);

        // Send database notifications to other users for invoice and receipt actions
        $monitoredActions = [
            'created_invoice', 'updated_invoice', 'deleted_invoice', 'restored_invoice', 'purged_invoice',
            'created_receipt', 'updated_receipt', 'deleted_receipt', 'restored_receipt', 'purged_receipt'
        ];

        if (in_array($action, $monitoredActions)) {
            $currentUser = auth()->user();
            $currentUserId = $currentUser ? $currentUser->id : null;
            $userName = $currentUser ? $currentUser->name : 'System';

            $title = '';
            $type = 'finance';
            $actionUrl = null;
            $actionLabel = null;

            switch ($action) {
                case 'created_invoice':
                    $title = 'Invoice Created';
                    $type = 'finance';
                    if ($model) {
                        $actionUrl = route('invoices.show', $model->id);
                        $actionLabel = 'View Invoice';
                    }
                    break;
                case 'updated_invoice':
                    $title = 'Invoice Updated';
                    $type = 'finance';
                    if ($model) {
                        $actionUrl = route('invoices.show', $model->id);
                        $actionLabel = 'View Invoice';
                    }
                    break;
                case 'deleted_invoice':
                    $title = 'Invoice Soft Deleted';
                    $type = 'critical';
                    break;
                case 'restored_invoice':
                    $title = 'Invoice Restored';
                    $type = 'finance';
                    if ($model) {
                        $actionUrl = route('invoices.show', $model->id);
                        $actionLabel = 'View Invoice';
                    }
                    break;
                case 'purged_invoice':
                    $title = 'Invoice Purged Permanently';
                    $type = 'critical';
                    break;
                case 'created_receipt':
                    $title = 'Receipt Created';
                    $type = 'finance';
                    if ($model) {
                        $actionUrl = route('receipts.show', $model->id);
                        $actionLabel = 'View Receipt';
                    }
                    break;
                case 'updated_receipt':
                    $title = 'Receipt Updated';
                    $type = 'finance';
                    if ($model) {
                        $actionUrl = route('receipts.show', $model->id);
                        $actionLabel = 'View Receipt';
                    }
                    break;
                case 'deleted_receipt':
                    $title = 'Receipt Soft Deleted';
                    $type = 'critical';
                    break;
                case 'restored_receipt':
                    $title = 'Receipt Restored';
                    $type = 'finance';
                    if ($model) {
                        $actionUrl = route('receipts.show', $model->id);
                        $actionLabel = 'View Receipt';
                    }
                    break;
                case 'purged_receipt':
                    $title = 'Receipt Purged Permanently';
                    $type = 'critical';
                    break;
            }

            $message = "{$userName}: {$description}";

            $usersToNotify = \App\Models\User::when($currentUserId, function ($q) use ($currentUserId) {
                return $q->where('id', '!=', $currentUserId);
            })->get();

            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\SystemActivityNotification($title, $message, $type, $actionUrl, $actionLabel));
            }
        }

        return $log;
    }
}
