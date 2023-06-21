<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminPermission;

interface AdminPermissionRepositoryInterface
{
    /**
     * @return array<AdminPermission>
     */
    public function findByAdminId(int $adminId): array;

    public function store(AdminPermission $adminPermission): void;
}
