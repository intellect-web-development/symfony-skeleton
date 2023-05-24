<?php

declare(strict_types=1);

namespace App\Auth\Security;

class JWT
{
    public string $privateKey;
    public string $publicKey;
    public string $passPhrase;

    public function __construct(
        string $privateKey,
        string $publicKey,
        string $passPhrase,
    ) {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->passPhrase = $passPhrase;
    }
}
