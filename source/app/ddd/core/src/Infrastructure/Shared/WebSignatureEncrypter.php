<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Domain\WebSignature\WebSignature;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use AppCore\Domain\WebSignature\WebSignatureInterface;

use function assert;
use function method_exists;
use function str_replace;

class WebSignatureEncrypter implements WebSignatureEncrypterInterface
{
    public function __construct(
        private readonly EncrypterInterface $encrypter,
        private readonly SecureRandomInterface $secureRandom,
    ) {
    }

    public function encrypt(WebSignatureInterface $webSignature): string
    {
        $serialized = $webSignature->serialize($this->secureRandom->randomBytes(5));
        $encrypted = $this->encrypter->encrypt($serialized);

        return $this->encodeUrlSafe($encrypted);
    }

    public function decrypt(string $string, string $className = WebSignature::class): WebSignatureInterface
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
