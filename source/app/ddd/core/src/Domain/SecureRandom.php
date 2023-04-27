<?php

declare(strict_types=1);

namespace AppCore\Domain;

use Ray\Di\Di\Named;

use function hash;
use function hash_hmac;
use function random_bytes;

class SecureRandom implements SecureRandomInterface
{
    public function __construct(
        #[Named('hash_salt')] private readonly string $hashSalt,
    ) {
    }

    public function hash(string $data, string $algo = 'sha256'): string
    {
        return hash($algo, $data);
    }

    public function hmac(string $data, string $algo = 'sha256'): string
    {
        return hash_hmac($algo, $data, $this->hashSalt);
    }

    public function randomBytes(int $length): string
    {
        return random_bytes($length);
    }
}
