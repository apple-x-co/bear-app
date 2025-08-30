<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

class AdminEntityFactory
{
    /**
     * @param positive-int $id
     * @param int<0, 1>    $active
     */
    public static function factory(
        int $id,
        string $username,
        string $password,
        string $displayName,
        int $active,
        string $createdDate,
        string $updatedDate,
    ): AdminEntity {
        return new AdminEntity(
            $id,
            $username,
            $password,
            $displayName,
            $active,
            new DateTimeImmutable($createdDate),
            new DateTimeImmutable($updatedDate),
        );
    }
}
