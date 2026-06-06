<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepala_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class);
    }
}
