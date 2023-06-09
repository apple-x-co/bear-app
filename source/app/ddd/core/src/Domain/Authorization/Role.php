<?php

declare(strict_types=1);

namespace AppCore\Domain\Authorization;

class Role implements RoleInterface
{
    /**
     * @param array<Policy> $policies
     */
    public function __construct(
        public readonly array $policies,
    ) {
    }

    public function allowed(Permission $permission, ?int $objectId = null): bool
    {
        foreach ($this->policies as $policy) {
            if (! $policy->hasPermission($permission)) {
                continue;
            }

            if ($objectId === null || $policy->containObjectId($objectId)) {
                return true;
            }
        }

        return false;
    }

    /** {@inheritDoc} */
    public function allowedObjectIds(Permission $permission): ?array
    {
        foreach ($this->policies as $policy) {
            if (! $policy->hasPermission($permission)) {
                continue;
            }

            return $policy->objectIds;
        }

        return null;
    }
}
