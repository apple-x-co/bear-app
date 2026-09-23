<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final readonly class AdminTokenEntity
{
    public function __construct(
        public int $id,
        public int $adminId,
        public string $token,
        public DateTimeImmutable $expireDate,
        public DateTimeImmutable $createdDate,
    ) {
    }
}
