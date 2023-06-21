<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

class AdminPermissionEntityFactory
{
    public static function factory(
        int $id,
        int $adminId,
        string $access,
        string $resourceName,
        string $permissionName,
        string $createdAt,
    ): AdminPermissionEntity {
        return new AdminPermissionEntity(
            $id,
            $adminId,
            $access,
            $resourceName,
            $permissionName,
            new DateTimeImmutable($createdAt),
        );
    }
}
