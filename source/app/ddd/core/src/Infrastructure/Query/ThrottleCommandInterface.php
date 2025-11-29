<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
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
        DateTimeInterface $expireDate,
        DateTimeInterface|null $createdDate = null,
        DateTimeInterface|null $updatedDate = null,
    ): void;

    #[DbQuery('throttles/throttle_update')]
    public function update(
        int $id,
        string $remoteIp,
        int $iterationCount,
        DateTimeInterface $expireDate,
        DateTimeInterface|null $updatedDate = null,
    ): void;
}
