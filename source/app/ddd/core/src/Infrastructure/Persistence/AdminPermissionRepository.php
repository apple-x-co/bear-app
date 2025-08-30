<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\AccessControl\Access;
use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\AdminPermission\AdminPermission;
use AppCore\Domain\AdminPermission\AdminPermissionRepositoryInterface;
use AppCore\Infrastructure\Entity\AdminPermissionEntity;
use AppCore\Infrastructure\Query\AdminPermissionCommandInterface;
use AppCore\Infrastructure\Query\AdminPermissionQueryInterface;

use function array_map;
use function array_values;

readonly class AdminPermissionRepository implements AdminPermissionRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private AdminPermissionCommandInterface $adminPermissionCommand,
        private AdminPermissionQueryInterface $adminPermissionQuery,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function findByAdminId(int $adminId): array
    {
        $adminPermissionEntities = $this->adminPermissionQuery->list($adminId);

        return array_values(
            array_map(
                function (AdminPermissionEntity $item) {
                    return $this->entityToModel($item);
                },
                $adminPermissionEntities,
            ),
        );
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    private function entityToModel(AdminPermissionEntity $entity): AdminPermission
    {
        return AdminPermission::reconstruct(
            $entity->id,
            $entity->adminId,
            Access::from($entity->access),
            $entity->resourceName,
            Permission::from($entity->permissionName),
            $entity->createdDate,
        );
    }

    public function store(AdminPermission $adminPermission): void
    {
        $adminPermission->id === null ? $this->insert($adminPermission) : $this->update($adminPermission);
    }

    public function insert(AdminPermission $adminPermission): void
    {
        $this->adminPermissionCommand->add(
            $adminPermission->adminId,
            $adminPermission->access->value,
            $adminPermission->resourceName,
            $adminPermission->permission->value,
        );
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(AdminPermission $adminPermission): void
    {
        // void
    }
}
