<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminPermission;

use AppCore\Domain\AccessControl\Access;
use AppCore\Domain\AccessControl\Permission;
use DateTimeImmutable;

class AdminPermission
{
    public const DEFAULT_RESOURCE_NAMES = ['Settings'];

    public function __construct(
        public readonly int $adminId,
        public readonly Access $access,
        public readonly string $resourceName,
        public readonly Permission $permission,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?int $id = null,
    ) {
    }

    public static function reconstruct(
        ?int $id,
        int $adminId,
        Access $access,
        string $resourceName,
        Permission $permission,
        ?DateTimeImmutable $createdAt = null,
    ): self {
        return new self(
            $adminId,
            $access,
            $resourceName,
            $permission,
            $createdAt,
            $id,
        );
    }
}
