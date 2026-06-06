<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Encounter extends Model
{
    protected $guarded = [];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'metadata' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function nursingAssessment(): HasOne
    {
        return $this->hasOne(NursingAssessment::class);
    }

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function labOrders(): HasMany
    {
        return $this->hasMany(LabOrder::class);
    }

    public function radiologyOrders(): HasMany
    {
        return $this->hasMany(RadiologyOrder::class);
    }

    public function billingInvoice(): HasOne
    {
        return $this->hasOne(BillingInvoice::class);
    }

    public function sepDocument(): HasOne
    {
        return $this->hasOne(BPJSSepDocument::class);
    }
}
