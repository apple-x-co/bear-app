<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

class AdminEmailEntityFactory
{
    public static function factory(
        int $id,
        int $adminId,
        string $emailAddress,
        ?string $verifiedAt,
        string $createdAt,
        string $updatedAt,
    ): AdminEmailEntity {
        return new AdminEmailEntity(
            $id,
            $adminId,
            $emailAddress,
            $verifiedAt === null ? null : new DateTimeImmutable($verifiedAt),
            new DateTimeImmutable($createdAt),
            new DateTimeImmutable($updatedAt),
        );
    }
}
