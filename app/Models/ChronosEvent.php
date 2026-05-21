<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChronosEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'color',
        'category',
        'client_id',
        'user_id',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
