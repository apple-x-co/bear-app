<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Entity;

use DateTimeImmutable;
use Ray\MediaQuery\CamelCaseTrait;

class AdminEntity
{
    use CamelCaseTrait;

    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $password,
        public readonly int $active,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
    ) {
    }
}
