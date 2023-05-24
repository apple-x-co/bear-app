<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use AppCore\Domain\WebSignature\InvalidSignatureException;
use AppCore\Domain\WebSignature\WebSignatureInterface;
use DateTimeImmutable;

use function serialize;
use function unserialize;

class EmailWebSignature implements WebSignatureInterface
{
    public function __construct(
        public readonly int $adminId,
        public readonly DateTimeImmutable $expiresAt,
        public readonly string $address,
    ) {
    }

    public function serialize(string $random): string
    {
        return serialize([
            '_' => $random,
            'id' => $this->adminId,
            'timestamp' => $this->expiresAt->getTimestamp(),
            'address' => $this->address,
        ]);
    }

    public static function deserialize(string $string): self
    {
        $array = unserialize($string, ['allowed_classes' => false, 'max_depth' => 1]);
        if (! isset($array['_'], $array['id'], $array['timestamp'], $array['address'])) {
            throw new InvalidSignatureException();
        }

        return new self(
            (int) $array['id'],
            (new DateTimeImmutable())->setTimestamp($array['timestamp']),
            $array['address'],
        );
    }
}
