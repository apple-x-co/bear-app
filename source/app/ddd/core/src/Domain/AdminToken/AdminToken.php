<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminToken;

use DateTimeImmutable;

class AdminToken
{
    public function __construct(
        public readonly int $adminId,
        public readonly string $token,
        public readonly DateTimeImmutable $expireDate,
        public readonly DateTimeImmutable|null $createdDate = null,
        public readonly int|null $id = null,
    ) {
    }

    public static function reconstruct(
        int $id,
        int $adminId,
        string $token,
        DateTimeImmutable $expireDate,
        DateTimeImmutable $createdDate,
    ): self {
        return new self(
            $adminId,
            $token,
            $expireDate,
            $createdDate,
            $id,
        );
    }

    public function isExpired(): bool
    {
        $now = new DateTimeImmutable();

        return $this->expireDate < $now;
    }
}
