<?php

declare(strict_types=1);

namespace AppCore\Domain\Authorization;

interface RoleInterface
{
    public function allowed(Permission $permission, ?int $objectId = null): bool;

    /**
     * @return array<int>|null
     */
    public function allowedObjectIds(Permission $permission): ?array;
}
