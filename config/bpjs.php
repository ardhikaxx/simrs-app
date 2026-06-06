<?php

return [
    'vclaim' => [
        'base_url' => env('BPJS_VCLAIM_URL', 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest'),
        'sandbox_url' => env('BPJS_VCLAIM_SANDBOX_URL', 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev'),
        'cons_id' => env('BPJS_CONS_ID'),
        'secret_key' => env('BPJS_SECRET_KEY'),
        'user_key' => env('BPJS_USER_KEY'),
        'is_sandbox' => env('BPJS_SANDBOX', true),
    ],
    'eclaim' => [
        'base_url' => env('BPJS_ECLAIM_URL', 'https://apijkn.bpjs-kesehatan.go.id/eclaim'),
        'kode_rs' => env('SIMRS_KODE_RS', '3171012'),
    ],
];
