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
use function array_values;

readonly class AdminRepository implements AdminRepositoryInterface
{
    public function __construct(
        private AdminCommandInterface $adminCommand,
        private AdminEmailCommandInterface $adminEmailCommand,
        private AdminEmailQueryInterface $adminEmailQuery,
        private AdminQueryInterface $adminQuery,
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
     * @param list<AdminEmailEntity> $adminEmailEntities
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function entityToModel(AdminEntity $adminEntity, array $adminEmailEntities): Admin
    {
        $adminEmails = array_values(
            array_reduce(
                $adminEmailEntities,
                static function (array $carry, AdminEmailEntity $item) {
                    $carry[] = AdminEmail::reconstruct(
                        $item->id,
                        $item->adminId,
                        $item->emailAddress,
                        $item->verifiedDate,
                        $item->createdDate,
                        $item->updatedDate,
                    );

                    return $carry;
                },
                [],
            ),
        );

        return Admin::reconstruct(
            $adminEntity->id,
            $adminEntity->username,
            $adminEntity->password,
            $adminEntity->displayName,
            (bool) $adminEntity->active,
            $adminEmails,
            $adminEntity->createdDate,
            $adminEntity->updatedDate,
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

        $adminId = $array['id'];

        foreach ($admin->emails as $email) {
            $array = $this->adminEmailCommand->add(
                $adminId,
                $email->emailAddress,
            );

            if (! ($email->verifiedDate instanceof DateTimeImmutable)) {
                continue;
            }

            $this->adminEmailCommand->verified($array['id'], $email->verifiedDate);
        }

        $admin->setNewId($adminId);
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
                    $email->emailAddress,
                );

                continue;
            }

            if ($email->isRemoval()) {
                $this->adminEmailCommand->delete($email->id);

                continue;
            }

            if (! ($email->verifiedDate instanceof DateTimeImmutable)) {
                continue;
            }

            $this->adminEmailCommand->verified($email->id, $email->verifiedDate);
        }
    }
}
