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
        string $expireDate,
        string $createdDate,
        string $updatedDate,
    ): ThrottleEntity {
        return new ThrottleEntity(
            $id,
            $throttleKey,
            $remoteIp,
            $iterationCount,
            $maxAttempts,
            $interval,
            new DateTimeImmutable($expireDate),
            new DateTimeImmutable($createdDate),
            new DateTimeImmutable($updatedDate),
        );
    }
}
