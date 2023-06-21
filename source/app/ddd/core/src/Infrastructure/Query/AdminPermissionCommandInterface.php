<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminPermissionCommandInterface
{
    #[DbQuery('admin_permission_add')]
    public function add(
        int $adminId,
        string $access,
        string $resourceName,
        string $permissionName,
        ?DateTimeImmutable $createdAt = null,
    ): void;
}
