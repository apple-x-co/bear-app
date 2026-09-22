<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final readonly class VerificationCodeEntity
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $emailAddress,
        public string $url,
        public string $code,
        public DateTimeImmutable $expireDate,
        public DateTimeImmutable|null $verifiedDate,
        public DateTimeImmutable $createdDate,
        public DateTimeImmutable $updatedDate,
    ) {
    }
}
