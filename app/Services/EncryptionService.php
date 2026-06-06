<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptionService
{
    public function encrypt(?string $value): ?string
    {
        return $value === null ? null : Crypt::encryptString($value);
    }

    public function decrypt(?string $payload): ?string
    {
        return $payload === null ? null : Crypt::decryptString($payload);
    }
}
