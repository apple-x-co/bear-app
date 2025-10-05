<?php

declare(strict_types=1);

namespace AppCore\Domain\WebSignature;

interface UrlSignatureEncrypterInterface
{
    public function encrypt(UrlSignatureInterface $webSignature): string;

    /**
     * @param class-string<T> $className
     *
     * @return T
     *
     * @template T of object
     */
    public function decrypt(string $string, string $className = UrlSignature::class): object;
}
