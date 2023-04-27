<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Admin\Admin;
use AppCore\Domain\Admin\AdminNotFoundException;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use MyVendor\MyProject\Entity\AdminEntity;
use MyVendor\MyProject\Query\AdminQueryInterface;

class AdminRepository implements AdminRepositoryInterface
{
    public function __construct(
        private readonly AdminQueryInterface $query,
    ) {
    }

    public function findById(int $id): Admin
    {
        $entity = $this->query->item($id);
        if ($entity === null) {
            throw new AdminNotFoundException((string) $id);
        }

        return $this->entityToModel($entity);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function entityToModel(AdminEntity $entity): Admin
    {
        return Admin::reconstruct(
            $entity->id,
            $entity->username,
            $entity->password,
            (bool) $entity->active,
            $entity->createdAt,
            $entity->updatedAt,
        );
    }
}
