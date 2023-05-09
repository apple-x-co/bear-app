<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Admin\Admin;
use AppCore\Domain\Admin\AdminEmail;
use AppCore\Domain\Admin\AdminNotFoundException;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Infrastructure\Entity\AdminEmailEntity;
use AppCore\Infrastructure\Entity\AdminEntity;
use AppCore\Infrastructure\Query\AdminEmailQueryInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;

use function array_reduce;

class AdminRepository implements AdminRepositoryInterface
{
    public function __construct(
        private readonly AdminEmailQueryInterface $adminEmailQuery,
        private readonly AdminQueryInterface $adminQuery,
    ) {
    }

    public function findById(int $id): Admin
    {
        $adminEntity = $this->adminQuery->item($id);
        if ($adminEntity === null) {
            throw new AdminNotFoundException((string) $id);
        }

        $adminEmailEntities = $this->adminEmailQuery->list($adminEntity->id);

        return $this->entityToModel($adminEntity, $adminEmailEntities);
    }

    /**
     * @param array<AdminEmailEntity> $adminEmailEntities
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function entityToModel(AdminEntity $adminEntity, array $adminEmailEntities): Admin
    {
        $adminEmails = array_reduce(
            $adminEmailEntities,
            static function (array $carry, AdminEmailEntity $item) {
                $carry[] = AdminEmail::reconstruct(
                    $item->id,
                    $item->adminId,
                    $item->emailAddress,
                    $item->verifiedAt,
                    $item->createdAt,
                    $item->updatedAt,
                );

                return $carry;
            },
            [],
        );

        return Admin::reconstruct(
            $adminEntity->id,
            $adminEntity->username,
            $adminEntity->password,
            $adminEntity->displayName,
            (bool) $adminEntity->active,
            $adminEmails,
            $adminEntity->createdAt,
            $adminEntity->updatedAt,
        );
    }
}
