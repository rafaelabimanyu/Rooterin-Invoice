<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'technician_names',
        'cause_of_clog',
        'warranty_info',
        'documentation_links',
        'ppn_percentage',
        'pph_percentage',
        'ppn_amount',
        'pph_amount',
    ];

    protected $casts = [
        'documentation_links' => 'array',
    ];

    /**
     * Get the transaction that owns the project details.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
