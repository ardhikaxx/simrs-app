<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    protected $guarded = [];

    protected $casts = [
        'icd10_sekunder' => 'array',
        'data_spesifik_poli' => 'array',
        'signed_at' => 'datetime',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function bhps()
    {
        return $this->belongsToMany(InventoryBhp::class, 'medical_record_bhp')
            ->withPivot(['jumlah', 'harga_satuan', 'subtotal'])
            ->withTimestamps();
    }
}
