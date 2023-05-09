<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;
use Ray\MediaQuery\CamelCaseTrait;

class AdminEmailEntity
{
    use CamelCaseTrait;

    public function __construct(
        public readonly int $id,
        public readonly int $adminId,
        public readonly string $emailAddress,
        public readonly ?DateTimeImmutable $verifiedAt,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
    ) {
    }
}
