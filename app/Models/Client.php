<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_client',
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

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
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
