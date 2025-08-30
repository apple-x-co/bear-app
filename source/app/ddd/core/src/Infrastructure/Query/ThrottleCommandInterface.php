<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface ThrottleCommandInterface
{
    #[DbQuery('throttles/throttle_add')]
    public function add(
        string $throttleKey,
        string $remoteIp,
        int $iterationCount,
        int $maxAttempts,
        string $interval,
        DateTimeImmutable $expireDate,
        DateTimeImmutable|null $createdDate = null,
        DateTimeImmutable|null $updatedDate = null,
    ): void;

    #[DbQuery('throttles/throttle_update')]
    public function update(
        int $id,
        string $remoteIp,
        int $iterationCount,
        DateTimeImmutable $expireDate,
        DateTimeImmutable|null $updatedDate = null,
    ): void;
}
