<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class);
    }

    public function getAgeAttribute(): int
    {
        return $this->tgl_lahir->age;
    }
}
