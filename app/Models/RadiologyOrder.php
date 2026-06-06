<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RadiologyOrder extends Model
{
    protected $guarded = [];

    protected $casts = [
        'ordered_at' => 'datetime',
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

    public function radiographer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'radiographer_id');
    }

    public function result(): HasOne
    {
        return $this->hasOne(RadiologyResult::class);
    }
}
