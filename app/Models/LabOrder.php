<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabOrder extends Model
{
    protected $guarded = [];

    protected $casts = [
        'ordered_at' => 'datetime',
        'sample_received_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function analyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analyst_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(LabResult::class);
    }

    public function panels(): BelongsToMany
    {
        return $this->belongsToMany(LabPanel::class, 'lab_order_panels')
            ->withPivot('tarif')
            ->withTimestamps();
    }
}
