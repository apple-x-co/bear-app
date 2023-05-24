<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Admin\Admin;
use AppCore\Domain\Admin\AdminEmail;
use AppCore\Domain\Admin\AdminNotFoundException;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Infrastructure\Entity\AdminEmailEntity;
use AppCore\Infrastructure\Entity\AdminEntity;
use AppCore\Infrastructure\Query\AdminCommandInterface;
use AppCore\Infrastructure\Query\AdminEmailCommandInterface;
use AppCore\Infrastructure\Query\AdminEmailQueryInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use DateTimeImmutable;

use function array_reduce;

class AdminRepository implements AdminRepositoryInterface
{
    public function __construct(
        private readonly AdminCommandInterface $adminCommand,
        private readonly AdminEmailCommandInterface $adminEmailCommand,
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

    public function findByEmailAddress(string $emailAddress): ?Admin
    {
        $adminEntity = $this->adminQuery->itemByEmailAddress($emailAddress);
        if ($adminEntity === null) {
            return null;
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

    public function store(Admin $admin): void
    {
        $admin->id === null ? $this->insert($admin) : $this->update($admin);
    }

    private function insert(Admin $admin): void
    {
        $array = $this->adminCommand->add(
            $admin->username,
            $admin->password,
            $admin->displayName,
            $admin->active ? 1 : 0,
        );

        foreach ($admin->emails as $email) {
            $array = $this->adminEmailCommand->add(
                $array['id'],
                $email->emailAddress,
            );

            if (! ($email->verifiedAt instanceof DateTimeImmutable)) {
                continue;
            }

            $this->adminEmailCommand->verified($array['id'], $email->verifiedAt);
        }
    }

    private function update(Admin $admin): void
    {
        if ($admin->id === null) {
            return;
        }

        $this->adminCommand->update(
            $admin->id,
            $admin->username,
            $admin->displayName,
            $admin->active ? 1 : 0,
        );

        foreach ($admin->emails as $email) {
            if ($email->id === null) {
                $this->adminEmailCommand->add(
                    $admin->id,
                    $email->emailAddress
                );

                continue;
            }

            if ($email->isRemoval()) {
                $this->adminEmailCommand->delete($email->id);

                continue;
            }

            if (! ($email->verifiedAt instanceof DateTimeImmutable)) {
                continue;
            }

            $this->adminEmailCommand->verified($email->id, $email->verifiedAt);
        }
    }
}
