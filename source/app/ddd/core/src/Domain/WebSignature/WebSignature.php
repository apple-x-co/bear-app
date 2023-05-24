<?php

declare(strict_types=1);

namespace AppCore\Domain\WebSignature;

use DateTimeImmutable;

use function serialize;
use function unserialize;

class WebSignature implements WebSignatureInterface
{
    public function __construct(
        public readonly DateTimeImmutable $expiresAt,
        public readonly string $address,
    ) {
    }

    public function serialize(string $random): string
    {
        return serialize([
            '_' => $random,
            'timestamp' => $this->expiresAt->getTimestamp(),
            'address' => $this->address,
        ]);
    }

    public static function deserialize(string $string): self
    {
        $array = unserialize($string, ['allowed_classes' => false, 'max_depth' => 1]);
        if (! isset($array['_'], $array['timestamp'], $array['address'])) {
            throw new InvalidSignatureException();
        }

        return new self(
            (new DateTimeImmutable())->setTimestamp($array['timestamp']),
            $array['address'],
        );
    }
}
