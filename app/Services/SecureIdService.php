<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class SecureIdService
{
    public static function encrypt(int $id): string
    {
        return Crypt::encryptString((string) $id);
    }

    public static function decrypt(string $encryptedId): ?int
    {
        try {
            return (int) Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            return null; // Return null if decryption fails
        }
    }
}
