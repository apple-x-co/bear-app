<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use AppCore\Domain\RemovalTrait;
use DateTimeImmutable;

class AdminEmail
{
    use RemovalTrait;

    public function __construct(
        public readonly int $adminId,
        public readonly string $emailAddress,
        public readonly ?DateTimeImmutable $verifiedAt = null,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?DateTimeImmutable $updatedAt = null,
        public readonly ?int $id = null,
    ) {
    }

    public static function reconstruct(
        int $id,
        int $adminId,
        string $emailAddress,
        ?DateTimeImmutable $verifiedAt,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $adminId,
            $emailAddress,
            $verifiedAt,
            $createdAt,
            $updatedAt,
            $id,
        );
    }

    public function verified(): self
    {
        return new self(
            $this->adminId,
            $this->emailAddress,
            new DateTimeImmutable(),
            $this->createdAt,
            $this->updatedAt,
            $this->id,
        );
    }
}
