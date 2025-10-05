<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Domain\WebSignature\UrlSignature;
use AppCore\Domain\WebSignature\UrlSignatureEncrypterInterface;
use AppCore\Domain\WebSignature\UrlSignatureInterface;

use function assert;
use function method_exists;
use function str_replace;

readonly class UrlSignatureEncrypter implements UrlSignatureEncrypterInterface
{
    public function __construct(
        private EncrypterInterface $encrypter,
        private SecureRandomInterface $secureRandom,
    ) {
    }

    public function encrypt(UrlSignatureInterface $webSignature): string
    {
        $serialized = $webSignature->serialize($this->secureRandom->randomBytes(5));
        $encrypted = $this->encrypter->encrypt($serialized);

        return $this->encodeUrlSafe($encrypted);
    }

    public function decrypt(string $string, string $className = UrlSignature::class): UrlSignatureInterface
    {
        $decrypted = $this->encrypter->decrypt($this->decodeUrlSafe($string));

        assert(method_exists($className, 'deserialize'));

        return $className::deserialize($decrypted);
    }

    private function encodeUrlSafe(string $string): string
    {
        return str_replace(['+', '/', '='], ['_', '-', '.'], $string);
    }

    private function decodeUrlSafe(string $string): string
    {
        return str_replace(['_', '-', '.'], ['+', '/', '='], $string);
    }
}
