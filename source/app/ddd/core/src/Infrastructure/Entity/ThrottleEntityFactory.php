<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

class ThrottleEntityFactory
{
    public static function factory(
        int $id,
        string $throttleKey,
        string $remoteIp,
        int $iterationCount,
        int $maxAttempts,
        string $interval,
        string $expireAt,
        string $createdAt,
        string $updatedAt,
    ): ThrottleEntity {
        return new ThrottleEntity(
            $id,
            $throttleKey,
            $remoteIp,
            $iterationCount,
            $maxAttempts,
            $interval,
            new DateTimeImmutable($expireAt),
            new DateTimeImmutable($createdAt),
            new DateTimeImmutable($updatedAt),
        );
    }
}
