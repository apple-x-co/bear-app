<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminToken;

use DateTimeImmutable;

class AdminToken
{
    public function __construct(
        public readonly int $adminId,
        public readonly string $token,
        public readonly DateTimeImmutable $expireAt,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?int $id = null,
    ) {
    }

    public static function reconstruct(
        int $id,
        int $adminId,
        string $token,
        DateTimeImmutable $expireAt,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            $adminId,
            $token,
            $expireAt,
            $createdAt,
            $id,
        );
    }

    public function isExpired(): bool
    {
        $now = new DateTimeImmutable();

        return $this->expireAt < $now;
    }
}
