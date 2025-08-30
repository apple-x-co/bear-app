<?php

declare(strict_types=1);

namespace AppCore\Domain\WebSignature;

use DateTimeImmutable;

use function serialize;
use function unserialize;

class WebSignature implements WebSignatureInterface
{
    public function __construct(
        public readonly DateTimeImmutable $expiresDate,
        public readonly string $address,
    ) {
    }

    public function serialize(string $random): string
    {
        return serialize([
            '_' => $random,
            'timestamp' => $this->expiresDate->getTimestamp(),
            'address' => $this->address,
        ]);
    }

    public static function deserialize(string $string): self
    {
        $array = unserialize($string, ['allowed_classes' => false]);
        if (! isset($array['_'], $array['timestamp'], $array['address'])) {
            throw new InvalidSignatureException();
        }

        return new self(
            (new DateTimeImmutable())->setTimestamp($array['timestamp']),
            $array['address'],
        );
    }
}
