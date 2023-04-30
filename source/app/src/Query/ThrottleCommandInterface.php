<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface ThrottleCommandInterface
{
    #[DbQuery('throttle_add')]
    public function add(
        string $throttleKey,
        string $remoteIp,
        int $iterationCount,
        int $maxAttempts,
        string $interval,
        DateTimeImmutable $expireAt,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ): void;

    #[DbQuery('throttle_update')]
    public function update(
        int $id,
        string $remoteIp,
        int $iterationCount,
        DateTimeImmutable $expireAt,
        ?DateTimeImmutable $updatedAt = null,
    ): void;
}
