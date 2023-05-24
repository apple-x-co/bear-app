<?php

declare(strict_types=1);

namespace AppCore\Domain\WebSignature;

interface WebSignatureInterface
{
    public function serialize(string $random): string;

    public static function deserialize(string $string): self;
}
