<?php

declare(strict_types=1);

namespace AppCore\Domain\ResetPassword;

use DateTimeImmutable;

use function serialize;
use function unserialize;

class ResetPasswordSignature
{
    public function __construct(
        private readonly string $random,
        public readonly DateTimeImmutable $expiresAt,
        public readonly string $address,
    ) {
    }

    public function serialize(): string
    {
        return serialize([
            '_' => $this->random,
            'timestamp' => $this->expiresAt->getTimestamp(),
            'address' => $this->address,
        ]);
    }

    public static function deserialize(string $data): self
    {
        $array = unserialize($data, ['allowed_classes' => false, 'max_depth' => 1]);
        if (! isset($array['_'], $array['timestamp'], $array['address'])) {
            throw new InvalidSignatureException();
        }

        return new self(
            $array['_'],
            (new DateTimeImmutable())->setTimestamp($array['timestamp']),
            $array['address'],
        );
    }
}
