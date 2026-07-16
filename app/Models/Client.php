<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_client',
        'client_type',
        'industry_sector',
        'nama_client',
        'nama_perusahaan',
        'email',
        'no_hp',
        'npwp',
        'alamat',
        'kota',
        'provinsi',
        'catatan',
        'status',
    ];

    /**
     * Localized Client Type Label
     */
    public function getClientTypeLabelAttribute(): string
    {
        $key = strtolower($this->client_type ?? '');
        if (empty($key)) {
            return '';
        }
        $trans = __('ui.' . $key);
        return $trans !== 'ui.' . $key ? $trans : ucfirst($this->client_type);
    }

    /**
     * Localized Industry Sector Label
     */
    public function getIndustrySectorLabelAttribute(): string
    {
        $key = strtolower($this->industry_sector ?? '');
        if (empty($key)) {
            return '';
        }
        $trans = __('ui.' . $key);
        return $trans !== 'ui.' . $key ? $trans : ucfirst($this->industry_sector);
    }

    /**
     * Lucide Icon for Client Type
     */
    public function getTypeIconAttribute(): string
    {
        return match (strtolower($this->client_type ?? '')) {
            'individual', 'rumahan' => 'user',
            'corporate', 'perusahaan' => 'building-2',
            'government' => 'landmark',
            'foreign' => 'globe',
            default => 'briefcase',
        };
    }

    /**
     * Lucide Icon for Industry Sector
     */
    public function getSectorIconAttribute(): string
    {
        return match (strtolower($this->industry_sector ?? '')) {
            'fnb' => 'utensils',
            'healthcare' => 'activity',
            'manufacturing' => 'factory',
            'tech' => 'cpu',
            'education' => 'graduation-cap',
            default => 'briefcase',
        };
    }



    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function receipts(): HasManyThrough
    {
        return $this->hasManyThrough(Receipt::class, Invoice::class);
    }

    /**
     * Legacy alias for receipts
     */
    public function quotations(): HasMany
    {
        return $this->receipts();
    }

    /**
     * Generate unique client code (CLI-0001, etc.)
     */
    public static function generateCode(): string
    {
        $lastClient = self::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastClient ? ((int) substr($lastClient->kode_client, 4)) + 1 : 1;
        return 'CLI-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
