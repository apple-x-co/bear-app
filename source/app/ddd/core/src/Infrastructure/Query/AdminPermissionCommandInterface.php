<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminPermissionCommandInterface
{
    #[DbQuery('admins/admin_permission_add')]
    public function add(
        int $adminId,
        string $access,
        string $resourceName,
        string $permissionName,
        DateTimeInterface|null $createdDate = null,
    ): void;
}
