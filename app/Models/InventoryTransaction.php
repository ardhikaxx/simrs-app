<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    protected $guarded = [];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(InventoryMedicine::class, 'inventory_medicine_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
