<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use AppCore\Domain\WebSignature\InvalidSignatureException;
use AppCore\Domain\WebSignature\UrlSignatureInterface;
use DateTimeImmutable;

use function serialize;
use function unserialize;

class EmailUrlSignature implements UrlSignatureInterface
{
    public function __construct(
        public readonly int $adminId,
        public readonly DateTimeImmutable $expiresDate,
        public readonly string $address,
    ) {
    }

    public function serialize(string $random): string
    {
        return serialize([
            '_' => $random,
            'id' => $this->adminId,
            'timestamp' => $this->expiresDate->getTimestamp(),
            'address' => $this->address,
        ]);
    }

    public static function deserialize(string $string): self
    {
        $array = unserialize($string, ['allowed_classes' => false]);
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
