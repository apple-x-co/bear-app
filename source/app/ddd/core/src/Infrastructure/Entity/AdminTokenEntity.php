<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;
use Ray\MediaQuery\CamelCaseTrait;

class AdminTokenEntity
{
    use CamelCaseTrait;

    public function __construct(
        public readonly int $id,
        public readonly int $adminId,
        public readonly string $token,
        public readonly DateTimeImmutable $expireAt,
        public readonly DateTimeImmutable $createdAt,
    ) {
    }
}
