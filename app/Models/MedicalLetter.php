<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalLetter extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tgl_surat' => 'date',
        'tgl_kembali' => 'date',
    ];
}
