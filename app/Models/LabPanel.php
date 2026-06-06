<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabPanel extends Model
{
    protected $guarded = [];

    protected $casts = [
        'parameter_default' => 'array',
        'is_active' => 'boolean',
    ];
}
