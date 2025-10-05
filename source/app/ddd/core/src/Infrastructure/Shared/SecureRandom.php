<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Attribute\HashSalt;
use AppCore\Domain\SecureRandom\SecureRandomInterface;

use function base64_encode;
use function hash;
use function hash_hmac;
use function implode;
use function pack;
use function random_bytes;
use function random_int;
use function rtrim;
use function str_repeat;
use function strlen;
use function strtr;

readonly class SecureRandom implements SecureRandomInterface
{
    private const string ALPHA_NUM_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    private const string LOCK_TOKEN_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    public function __construct(
        #[HashSalt]
        private string $hashSalt,
    ) {
    }

    public function hash(string $data, string $algo = 'sha256'): string
    {
        return hash($algo, $data);
    }

    public function shortHash(string $data, string $algo = 'crc32'): string
    {
        return strtr(rtrim(base64_encode(pack('H*', hash($algo, $data))), '='), '+/', '-_');
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

    public function alphabetNumeric(int $length): string
    {
        $chars = self::ALPHA_NUM_CHARS;
        $max = strlen($chars) - 1;

        $result = [];

        for ($i = 0; $i < $length; $i++) {
            $result[] = $chars[random_int(0, $max)];
        }

        return implode('', $result);
    }

    public function lockToken(int $length = 16): string
    {
        $chars = self::LOCK_TOKEN_CHARS;
        $max = strlen($chars) - 1;

        $result = [];

        for ($i = 0; $i < $length; $i++) {
            $result[] = $chars[random_int(0, $max)];
        }

        return implode('', $result);
    }
}
