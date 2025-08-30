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
        public readonly DateTimeImmutable|null $verifiedDate,
        public readonly DateTimeImmutable $createdDate,
        public readonly DateTimeImmutable $updatedDate,
    ) {
    }
}
