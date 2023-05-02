<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

class AdminTokenEntityFactory
{
    public static function factory(
        int $id,
        int $adminId,
        string $token,
        string $expireAt,
        string $createdAt
    ): AdminTokenEntity {
        return new AdminTokenEntity(
            $id,
            $adminId,
            $token,
            new DateTimeImmutable($expireAt),
            new DateTimeImmutable($createdAt)
        );
    }
}
