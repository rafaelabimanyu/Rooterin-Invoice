<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'mode',
        'status',
        'business_unit_id',
        'client_id',
        'subtotal',
        'discount',
        'total',
        'due_date',
        'payment_date',
        'payment_method',
        'notes',
        'created_by',
        'deleted_by',
        'deletion_reason',
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    /**
     * Get the business unit that owns this transaction.
     */
    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    /**
     * Get the client that owns this transaction.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the project details associated with this transaction.
     */
    public function projectDetail(): HasOne
    {
        return $this->hasOne(ProjectDetail::class);
    }

    /**
     * Get the items for the transaction.
     */
    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Get the user who created the transaction.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who deleted the transaction.
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
