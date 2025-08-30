<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Throttle\Throttle;
use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use AppCore\Infrastructure\Entity\ThrottleEntity;
use AppCore\Infrastructure\Query\ThrottleCommandInterface;
use AppCore\Infrastructure\Query\ThrottleQueryInterface;

class ThrottleRepository implements ThrottleRepositoryInterface
{
    public function __construct(
        private readonly ThrottleCommandInterface $command,
        private readonly ThrottleQueryInterface $query,
    ) {
    }

    public function findByThrottleKey(string $throttleKey): Throttle|null
    {
        $entity = $this->query->itemByKey($throttleKey);
        if ($entity === null) {
            return null;
        }

        return $this->entityToModel($entity);
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    private function entityToModel(ThrottleEntity $entity): Throttle
    {
        return Throttle::reconstruct(
            $entity->id,
            $entity->throttleKey,
            $entity->remoteIp,
            $entity->iterationCount,
            $entity->maxAttempts,
            $entity->interval,
            $entity->expireDate,
            $entity->createdDate,
            $entity->updatedDate,
        );
    }

    public function store(Throttle $throttle): void
    {
        if ($throttle->id === null) {
            $this->command->add(
                $throttle->throttleKey,
                $throttle->remoteIp,
                $throttle->iterationCount,
                $throttle->maxAttempts,
                $throttle->interval,
                $throttle->expireDate,
            );

            return;
        }

        $this->command->update(
            $throttle->id,
            $throttle->remoteIp,
            $throttle->iterationCount,
            $throttle->expireDate,
        );
    }
}
