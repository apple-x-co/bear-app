<?php

declare(strict_types=1);

namespace AppCore\Domain\Throttle;

use DateInterval;
use DateTimeImmutable;

class Throttle
{
    public function __construct(
        public readonly string $throttleKey,
        public readonly string $remoteIp,
        public readonly int $iterationCount,
        public readonly int $maxAttempts,
        public readonly string $interval,
        public readonly DateTimeImmutable $expireAt,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?DateTimeImmutable $updatedAt = null,
        public readonly ?int $id = null,
    ) {
    }

    public static function reconstruct(
        int $id,
        string $throttleKey,
        string $remoteIp,
        int $iterationCount,
        int $maxAttempts,
        string $interval,
        DateTimeImmutable $expireAt,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $throttleKey,
            $remoteIp,
            $iterationCount,
            $maxAttempts,
            $interval,
            $expireAt,
            $createdAt,
            $updatedAt,
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

        return $this->expireAt < $now;
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    public function countUp(string $remoteIp): self
    {
        return new self(
            $this->throttleKey,
            $remoteIp,
            $this->iterationCount + 1,
            $this->maxAttempts,
            $this->interval,
            (new DateTimeImmutable())->add(DateInterval::createFromDateString($this->interval)),
            $this->createdAt,
            $this->updatedAt,
            $this->id,
        );
    }
}
