<?php

declare(strict_types=1);

namespace AppCore\Domain\VerifyEmail;

use DateTimeImmutable;

use function serialize;
use function unserialize;

class VerifyEmailSignature
{
    public function __construct(
        private readonly string $random,
        public readonly DateTimeImmutable $expiresAt,
        public readonly int $id,
        public readonly string $address,
    ) {
    }

    public function serialize(): string
    {
        return serialize([
            '_' => $this->random,
            'timestamp' => $this->expiresAt->getTimestamp(),
            'id' => $this->id,
            'address' => $this->address,
        ]);
    }

    public static function deserialize(string $data): self
    {
        $array = unserialize($data, ['allowed_classes' => false, 'max_depth' => 1]);
        if (! isset($array['_'], $array['timestamp'], $array['id'], $array['address'])) {
            throw new InvalidSignatureException();
        }

        return new self(
            $array['_'],
            (new DateTimeImmutable())->setTimestamp($array['timestamp']),
            $array['id'],
            $array['address'],
        );
    }
}
