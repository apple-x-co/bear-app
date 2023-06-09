<?php

declare(strict_types=1);

namespace AppCore\Domain\Authorization;

use function in_array;

class Policy implements PolicyInterface
{
    /**
     * @param array<int>        $objectIds
     * @param array<Permission> $permissions
     */
    public function __construct(
        public readonly string $name,
        public readonly array $objectIds,
        public readonly array $permissions,
    ) {
    }

    public function hasPermission(Permission $permission): bool
    {
        if (in_array(Permission::All, $this->permissions, true)) {
            return true;
        }

        return in_array($permission, $this->permissions, true);
    }

    public function containObjectId(int $objectId): bool
    {
        if (in_array(Permission::All, $this->permissions, true)) {
            return true;
        }

        return in_array($objectId, $this->objectIds, true);
    }
}
