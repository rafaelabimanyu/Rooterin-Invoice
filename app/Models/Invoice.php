<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'client_id',
        'tanggal_invoice',
        'due_date',
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
        'tanggal_invoice' => 'date',
        'due_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate unique invoice number (ROOT-INV-0001, etc.)
     */
    public static function generateNumber(): string
    {
        $lastInvoice = self::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, 9)) + 1 : 1;
        return 'ROOT-INV-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
