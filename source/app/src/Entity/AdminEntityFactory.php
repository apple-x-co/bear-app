<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Entity;

use DateTimeImmutable;

class AdminEntityFactory
{
    public static function factory(
        int $id,
        string $username,
        string $password,
        int $active,
        string $createdAt,
        string $updatedAt
    ): AdminEntity {
        return new AdminEntity(
            $id,
            $username,
            $password,
            $active,
            new DateTimeImmutable($createdAt),
            new DateTimeImmutable($updatedAt),
        );
    }
}
