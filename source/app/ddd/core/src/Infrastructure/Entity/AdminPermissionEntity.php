<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final readonly class AdminPermissionEntity
{
    public function __construct(
        public int $id,
        public int $adminId,
        public string $access,
        public string $resourceName,
        public string $permissionName,
        public DateTimeImmutable $createdDate,
    ) {
    }
}
