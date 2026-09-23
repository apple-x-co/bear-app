<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final readonly class AdminEmailEntity
{
    public function __construct(
        public int $id,
        public int $adminId,
        public string $emailAddress,
        public DateTimeImmutable|null $verifiedDate,
        public DateTimeImmutable $createdDate,
        public DateTimeImmutable $updatedDate,
    ) {
    }
}
