<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingInvoice extends Model
{
    protected $guarded = [];

    protected $casts = [
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function billingDetails(): HasMany
    {
        return $this->hasMany(BillingDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getOutstandingAttribute(): float
    {
        return max(0, (float) $this->total_tagihan - (float) $this->total_dibayar);
    }
}
