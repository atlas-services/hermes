<?php

namespace App\Service;

class EncryptionService
{
    public function encrypt($data, $key)
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $encryptedData = sodium_crypto_secretbox($data, $nonce, $key);

        return [
            'encryptedData' => base64_encode($encryptedData),
            'nonce' => base64_encode($nonce),
        ];
    }

    public function decrypt($encryptedData, $nonce, $key)
    {
        $decodedData = base64_decode($encryptedData);
        $decodedNonce = base64_decode($nonce);

        return sodium_crypto_secretbox_open($decodedData, $decodedNonce, $key);
    }
}
