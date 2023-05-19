<?php

declare(strict_types=1);

namespace AppCore\Domain;

use Ray\Di\Di\Named;

use function hash;
use function hash_hmac;
use function random_bytes;
use function random_int;
use function str_repeat;

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

    public function randomNumbers(int $length): int
    {
        $min = (int) ('1' . str_repeat('0', $length - 1));
        $max = (int) str_repeat('9', $length);

        return random_int($min, $max);
    }
}
