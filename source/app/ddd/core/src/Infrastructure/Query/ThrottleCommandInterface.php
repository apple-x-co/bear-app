<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;

interface ThrottleCommandInterface
{
    #[DbQuery('throttles/throttle_increment')]
    public function increment(
        string $throttleKey,
        string $remoteIp,
        int $maxAttempts,
        string $interval,
        DateTimeInterface $expireDate,
        DateTimeInterface|null $createdDate = null,
        DateTimeInterface|null $updatedDate = null,
    ): void;
}
