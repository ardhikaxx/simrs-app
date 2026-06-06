<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class INACBGClaim extends Model
{
    protected $table = 'ina_cbg_claims';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'response_payload' => 'array',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function sepDocument(): BelongsTo
    {
        return $this->belongsTo(BPJSSepDocument::class, 'bpjs_sep_document_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(BillingInvoice::class, 'billing_invoice_id');
    }
}
