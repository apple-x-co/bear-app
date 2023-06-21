<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;
use Ray\MediaQuery\CamelCaseTrait;

class AdminPermissionEntity
{
    use CamelCaseTrait;

    public function __construct(
        public readonly int $id,
        public readonly int $adminId,
        public readonly string $access,
        public readonly string $resourceName,
        public readonly string $permissionName,
        public readonly DateTimeImmutable $createdAt,
    ) {
    }
}
