<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_number',
        'client_id',
        'tanggal_quotation',
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
        'tanggal_quotation' => 'date',
        'expiry_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNumber(): string
    {
        $lastQuotation = self::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastQuotation ? ((int) substr($lastQuotation->quotation_number, 9)) + 1 : 1;
        return 'ROOT-QUO-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
