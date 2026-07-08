<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'invoice_id',
        'amount_received',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    /**
     * Get the invoice that owns this receipt (1-to-1).
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Backward compatibility accessor for amount.
     */
    public function getAmountAttribute()
    {
        return $this->amount_received;
    }

    /**
     * Backward compatibility accessor for payment method.
     */
    public function getPaymentMethodAttribute()
    {
        return 'Transfer';
    }

    /**
     * Get the client associated with this receipt via invoice.
     */
    public function getClientAttribute()
    {
        if ($this->invoice && $this->invoice->client) {
            return $this->invoice->client;
        }
        return new Client([
            'nama_client' => 'Klien Tidak Ditemukan',
            'nama_perusahaan' => '-',
            'alamat' => '-',
            'kota' => '-',
            'provinsi' => '-',
            'no_hp' => '-',
        ]);
    }

    /**
     * Get the receipt status.
     */
    public function getStatusAttribute()
    {
        return 'paid';
    }

    /**
     * Get the receipt date.
     */
    public function getTanggalReceiptAttribute()
    {
        return $this->payment_date ?: ($this->created_at ?: now());
    }

    /**
     * Get the receipt expiry date.
     */
    public function getExpiryDateAttribute()
    {
        return $this->invoice ? $this->invoice->due_date : ($this->payment_date ?: ($this->created_at ?: now()));
    }

    /**
     * Get the total amount.
     */
    public function getTotalAttribute()
    {
        return $this->amount_received;
    }

    /**
     * Get the subtotal.
     */
    public function getSubtotalAttribute()
    {
        return $this->invoice ? $this->invoice->subtotal : $this->amount_received;
    }

    /**
     * Get the items associated with this receipt's invoice.
     */
    public function getItemsAttribute()
    {
        return $this->invoice ? $this->invoice->items : collect();
    }

    /**
     * Get the notes.
     */
    public function getNotesAttribute()
    {
        return $this->invoice ? $this->invoice->notes : '';
    }

    /**
     * Get the terms/condition.
     */
    public function getTermsConditionAttribute()
    {
        return $this->invoice ? $this->invoice->notes : '';
    }

    /**
     * Calculate virtual VAT tax percent based on nominal values.
     */
    public function getTaxPercentAttribute()
    {
        if ($this->invoice && $this->invoice->subtotal > 0) {
            return round(($this->invoice->ppn / $this->invoice->subtotal) * 100, 2);
        }
        return 0;
    }

    /**
     * Calculate virtual discount percent based on nominal values.
     */
    public function getDiscountPercentAttribute()
    {
        if ($this->invoice && $this->invoice->subtotal > 0) {
            return round(($this->invoice->discount / $this->invoice->subtotal) * 100, 2);
        }
        return 0;
    }
}
