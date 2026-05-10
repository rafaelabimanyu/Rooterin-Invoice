<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receipt_number',
        'client_id',
        'tanggal_receipt',
        'expiry_date',
        'status',
        'subtotal',
        'tax_percent',
        'discount_percent',
        'total',
        'notes_internal',
        'terms_condition',
        'created_by',
    ];

    protected $casts = [
        'tanggal_receipt' => 'date',
        'expiry_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNumber(): string
    {
        $lastReceipt = self::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastReceipt ? ((int) substr($lastReceipt->receipt_number, 9)) + 1 : 1;
        return 'ROOT-KWT-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
