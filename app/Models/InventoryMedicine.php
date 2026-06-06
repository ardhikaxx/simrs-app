<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryMedicine extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expired_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function prescriptionDetails(): HasMany
    {
        return $this->hasMany(PrescriptionDetail::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }
}
