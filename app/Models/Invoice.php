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
        'warranty',
        'status',
        'subtotal',
        'tax_percent',
        'discount_percent',
        'total',
        'notes_internal',
        'terms_condition',
        'bank_account_info',
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(InvoiceAttachment::class);
    }

    public function getAmountPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getAmountDueAttribute()
    {
        return $this->total - $this->amount_paid;
    }

    /**
     * Generate unique invoice number (JNJ-INV-0001, etc.)
     */
    public static function generateNumber(): string
    {
        $lastInvoice = self::withTrashed()->orderBy('id', 'desc')->first();
        if ($lastInvoice) {
            preg_match('/(\d+)$/', $lastInvoice->invoice_number, $matches);
            $number = isset($matches[1]) ? ((int) $matches[1]) + 1 : 1;
        } else {
            $number = 1;
        }
        return 'JNJ-INV-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
