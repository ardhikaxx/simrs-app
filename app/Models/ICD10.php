<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ICD10 extends Model
{
    protected $table = 'icd10';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
