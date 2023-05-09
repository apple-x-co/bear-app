<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

class AdminEntityFactory
{
    public static function factory(
        int $id,
        string $username,
        string $password,
        string $displayName,
        int $active,
        string $createdAt,
        string $updatedAt
    ): AdminEntity {
        return new AdminEntity(
            $id,
            $username,
            $password,
            $displayName,
            $active,
            new DateTimeImmutable($createdAt),
            new DateTimeImmutable($updatedAt),
        );
    }
}
