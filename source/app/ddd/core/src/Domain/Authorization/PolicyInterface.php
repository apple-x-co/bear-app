<?php

declare(strict_types=1);

namespace AppCore\Domain\Authorization;

interface PolicyInterface
{
    public function hasPermission(Permission $permission): bool;

    public function containObjectId(int $objectId): bool;
}
