<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;
use Ray\MediaQuery\CamelCaseTrait;

class VerificationCodeEntity
{
    use CamelCaseTrait;

    public function __construct(
        public readonly int $id,
        public readonly string $uuid,
        public readonly string $emailAddress,
        public readonly string $url,
        public readonly string $code,
        public readonly DateTimeImmutable $expireDate,
        public readonly DateTimeImmutable|null $verifiedDate,
        public readonly DateTimeImmutable $createdDate,
        public readonly DateTimeImmutable $updatedDate,
    ) {
    }
}
