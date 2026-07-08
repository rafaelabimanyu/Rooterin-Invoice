<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($businessUnit) {
            if (empty($businessUnit->slug) || $businessUnit->isDirty('name')) {
                $businessUnit->slug = \Illuminate\Support\Str::slug($businessUnit->name);
            }
        });
    }

    /**
     * Get all invoices under this business unit.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
