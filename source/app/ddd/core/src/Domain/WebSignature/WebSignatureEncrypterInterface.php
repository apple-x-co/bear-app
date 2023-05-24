<?php

declare(strict_types=1);

namespace AppCore\Domain\WebSignature;

interface WebSignatureEncrypterInterface
{
    public function encrypt(WebSignatureInterface $webSignature): string;

    /**
     * @param class-string<T> $className
     *
     * @return T
     *
     * @template T of object
     */
    public function decrypt(string $string, string $className = WebSignature::class): object;
}
