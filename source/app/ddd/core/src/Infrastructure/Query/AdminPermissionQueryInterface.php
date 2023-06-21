<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\AdminPermissionEntity;
use AppCore\Infrastructure\Entity\AdminPermissionEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminPermissionQueryInterface
{
    /** @return array<AdminPermissionEntity> */
    #[DbQuery('admin_permission_by_admin_id', factory: AdminPermissionEntityFactory::class)]
    public function list(int $adminId): array;
}
