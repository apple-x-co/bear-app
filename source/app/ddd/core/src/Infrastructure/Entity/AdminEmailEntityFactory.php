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
        string|null $verifiedDate,
        string $createdDate,
        string $updatedDate,
    ): AdminEmailEntity {
        return new AdminEmailEntity(
            $id,
            $adminId,
            $emailAddress,
            $verifiedDate === null ? null : new DateTimeImmutable($verifiedDate),
            new DateTimeImmutable($createdDate),
            new DateTimeImmutable($updatedDate),
        );
    }
}
