<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminPermission;

use AppCore\Domain\AccessControl\Access;
use AppCore\Domain\AccessControl\Permission;
use DateTimeImmutable;

class AdminPermission
{
    public const array DEFAULT_RESOURCE_NAMES = ['settings'];

    public function __construct(
        public readonly int $adminId,
        public readonly Access $access,
        public readonly string $resourceName,
        public readonly Permission $permission,
        public readonly DateTimeImmutable|null $createdDate = null,
        public readonly int|null $id = null,
    ) {
    }

    public static function reconstruct(
        int|null $id,
        int $adminId,
        Access $access,
        string $resourceName,
        Permission $permission,
        DateTimeImmutable|null $createdDate = null,
    ): self {
        return new self(
            $adminId,
            $access,
            $resourceName,
            $permission,
            $createdDate,
            $id,
        );
    }
}
