<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Attribute\EncryptPass;
use AppCore\Domain\Encrypter\EncrypterException;
use AppCore\Domain\Encrypter\EncrypterInterface;

use function base64_decode;
use function base64_encode;
use function openssl_decrypt;
use function openssl_encrypt;
use function openssl_random_pseudo_bytes;
use function rtrim;
use function str_pad;
use function strlen;
use function strtr;
use function substr;

use const OPENSSL_RAW_DATA;
use const STR_PAD_RIGHT;

final class CompactEncrypter implements EncrypterInterface
{
    private const string CIPHER_ALGO = 'aes-128-gcm';
    private const int TAG_LENGTH = 16;

    public function __construct(
        #[EncryptPass]
        private readonly string $pass,
    ) {
    }

    public function encrypt(string $text): string
    {
        $iv = openssl_random_pseudo_bytes(12, $strong);
        if ($strong === false) {
            throw new EncrypterException('');
        }

        $tag = '';
        $encrypted = openssl_encrypt(
            $text,
            self::CIPHER_ALGO,
            $this->pass,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            self::TAG_LENGTH,
        );

        if ($encrypted === false) {
            throw new EncrypterException('');
        }

        // バイナリで連結: IV(12) + Tag(16) + 暗号文
        $packed = $iv . $tag . $encrypted;

        // URL-safe Base64 (パディング除去)
        return rtrim(strtr(base64_encode($packed), '+/', '-_'), '=');
    }

    public function decrypt(string $payload): string
    {
        // URL-safe Base64デコード
        $payload = str_pad($payload, strlen($payload) % 4, '=', STR_PAD_RIGHT);
        $packed = base64_decode(strtr($payload, '-_', '+/'), true);

        if ($packed === false || strlen($packed) < 28) { // 12 + 16 = 最小28バイト
            throw new EncrypterException('');
        }

        // 分解
        $iv = substr($packed, 0, 12);
        $tag = substr($packed, 12, self::TAG_LENGTH);
        $encrypted = substr($packed, 28);

        $decrypted = openssl_decrypt(
            $encrypted,
            self::CIPHER_ALGO,
            $this->pass,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
        );

        if ($decrypted === false) {
            throw new EncrypterException();
        }

        return $decrypted;
    }
}
