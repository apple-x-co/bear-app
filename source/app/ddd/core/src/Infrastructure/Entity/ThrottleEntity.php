<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final readonly class ThrottleEntity
{
    public function __construct(
        public int $id,
        public string $throttleKey,
        public string $remoteIp,
        public int $iterationCount,
        public int $maxAttempts,
        public string $interval,
        public DateTimeImmutable $expireDate,
        public DateTimeImmutable $createdDate,
        public DateTimeImmutable $updatedDate,
    ) {
    }
}
