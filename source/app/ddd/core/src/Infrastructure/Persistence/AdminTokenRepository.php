<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\AdminToken\AdminToken;
use AppCore\Domain\AdminToken\AdminTokenRepositoryInterface;
use AppCore\Infrastructure\Entity\AdminTokenEntity;
use AppCore\Infrastructure\Query\AdminTokenCommandInterface;
use AppCore\Infrastructure\Query\AdminTokenQueryInterface;

class AdminTokenRepository implements AdminTokenRepositoryInterface
{
    public function __construct(
        private readonly AdminTokenCommandInterface $command,
        private readonly AdminTokenQueryInterface $query,
    ) {
    }

    public function findByToken(string $token): AdminToken|null
    {
        $entity = $this->query->itemByToken($token);
        if ($entity === null) {
            return null;
        }

        return $this->entityToModel($entity);
    }

    public function store(AdminToken $adminToken): void
    {
        $this->command->add(
            $adminToken->adminId,
            $adminToken->token,
            $adminToken->expireDate,
        );
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    private function entityToModel(AdminTokenEntity $entity): AdminToken
    {
        return AdminToken::reconstruct(
            $entity->id,
            $entity->adminId,
            $entity->token,
            $entity->expireDate,
            $entity->createdDate,
        );
    }
}
