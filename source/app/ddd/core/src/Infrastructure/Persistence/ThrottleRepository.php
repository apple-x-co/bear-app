<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Throttle\Throttle;
use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use AppCore\Infrastructure\Entity\ThrottleEntity;
use AppCore\Infrastructure\Query\ThrottleCommandInterface;
use AppCore\Infrastructure\Query\ThrottleQueryInterface;
use DateTimeImmutable;
use Override;
use Ray\AuraSqlModule\Annotation\Transactional;

use function assert;

class ThrottleRepository implements ThrottleRepositoryInterface
{
    public function __construct(
        private readonly ThrottleCommandInterface $command,
        private readonly ThrottleQueryInterface $query,
    ) {
    }

    /** @SuppressWarnings("PHPMD.StaticAccess") */
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

    /**
     * UPSERT と読み直しを同じトランザクションで行う。UPSERT の行ロックがコミットまで続くので、
     * ほかのリクエストの加算や clear() に割り込まれず、自分が数えた回数をそのまま読める
     */
    #[Override]
    #[Transactional]
    public function increment(
        string $throttleKey,
        string $remoteIp,
        int $maxAttempts,
        string $interval,
        DateTimeImmutable $expireDate,
    ): Throttle {
        $this->command->increment($throttleKey, $remoteIp, $maxAttempts, $interval, $expireDate);

        // UPSERT と読み直しのあいだに期限が切れても行を取れるよう、期限で絞り込まない
        $entity = $this->query->itemByKeyIncludingExpired($throttleKey);
        assert($entity instanceof ThrottleEntity);

        return $this->entityToModel($entity);
    }
}
