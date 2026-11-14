<?php

declare(strict_types=1);

namespace AppCore\Domain\Throttle;

use DateTimeImmutable;

readonly class Throttle
{
    public function __construct(
        public string $throttleKey,
        public string $remoteIp,
        public int $iterationCount,
        public int $maxAttempts,
        public string $interval,
        public DateTimeImmutable $expireDate,
        public DateTimeImmutable|null $createdDate = null,
        public DateTimeImmutable|null $updatedDate = null,
        public int|null $id = null,
    ) {
    }

    public static function reconstruct(
        int $id,
        string $throttleKey,
        string $remoteIp,
        int $iterationCount,
        int $maxAttempts,
        string $interval,
        DateTimeImmutable $expireDate,
        DateTimeImmutable $createdDate,
        DateTimeImmutable $updatedDate,
    ): self {
        return new self(
            $throttleKey,
            $remoteIp,
            $iterationCount,
            $maxAttempts,
            $interval,
            $expireDate,
            $createdDate,
            $updatedDate,
            $id,
        );
    }

    public function isExceeded(): bool
    {
        return $this->iterationCount > $this->maxAttempts;
    }

    public function isExpired(): bool
    {
        $now = new DateTimeImmutable();

        return $this->expireDate < $now;
    }
}
