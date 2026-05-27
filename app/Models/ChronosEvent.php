<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChronosEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status_type',
        'color',
        'client_id',
        'responsible_staff_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_staff_id');
    }

    public function responsibleStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_staff_id');
    }
}
