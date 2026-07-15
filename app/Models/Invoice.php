<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'business_unit_id',
        'client_id',
        'subtotal',
        'discount',
        'ppn',
        'pph',
        'total',
        'status',
        'due_date',
        'cause_of_problem',
        'notes',
        'technician_names',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the business unit that owns this invoice.
     */
    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    /**
     * Get the client that owns this invoice.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the items for the invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the receipt associated with this invoice (One-to-One).
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    /**
     * Get the attachments for the invoice.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(InvoiceAttachment::class);
    }

    /**
     * Backward compatibility accessor for tanggal_invoice.
     */
    public function getTanggalInvoiceAttribute()
    {
        return $this->created_at;
    }

    /**
     * Calculate virtual VAT tax percent based on nominal values.
     */
    public function getTaxPercentAttribute()
    {
        $base = $this->subtotal - $this->discount;
        if ($base > 0) {
            return round(($this->ppn / $base) * 100, 2);
        }
        return 0;
    }

    /**
     * Calculate virtual discount percent based on nominal values.
     */
    public function getDiscountPercentAttribute()
    {
        if ($this->subtotal > 0) {
            return round(($this->discount / $this->subtotal) * 100, 2);
        }
        return 0;
    }

    /**
     * Calculate virtual PPh percent based on nominal values.
     */
    public function getPphPercentAttribute()
    {
        $base = $this->subtotal - $this->discount;
        if ($base > 0) {
            return round(($this->pph / $base) * 100, 2);
        }
        return 0;
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Backward compatibility accessor for payments relation/collection.
     */
    public function getPaymentsAttribute()
    {
        if ($this->relationLoaded('payments')) {
            return $this->getRelationValue('payments');
        }
        return $this->payments()->get();
    }

    /**
     * Null-safe accessor for client relation.
     */
    public function getClientAttribute()
    {
        if ($this->relationLoaded('client') || $this->client_id) {
            $client = $this->getRelationValue('client');
            if ($client) {
                return $client;
            }
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
     * Get the user who created the invoice.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Backward compatibility accessor for creator relation.
     */
    public function getCreatorAttribute()
    {
        if ($this->relationLoaded('creator')) {
            return $this->getRelationValue('creator') ?: User::first() ?: new User(['name' => 'System']);
        }
        return $this->creator()->first() ?: User::first() ?: new User(['name' => 'System']);
    }

    /**
     * Get the remaining balance (sisa tagihan) for the invoice.
     */
    public function getAmountDueAttribute(): float
    {
        return (float) max(0, $this->total - $this->payments()->sum('amount'));
    }
}
