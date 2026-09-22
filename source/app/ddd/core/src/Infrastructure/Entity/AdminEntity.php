<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final readonly class AdminEntity
{
    /** @param positive-int $id */
    public function __construct(
        public int $id,
        public string $username,
        public string $password,
        public string $displayName,
        public int $active,
        public DateTimeImmutable $createdDate,
        public DateTimeImmutable $updatedDate,
    ) {
    }
}
