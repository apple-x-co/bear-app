<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use AppCore\Domain\RemovalTrait;
use DateTimeImmutable;

class AdminEmail
{
    use RemovalTrait;

    public function __construct(
        public readonly string $emailAddress,
        public readonly DateTimeImmutable|null $verifiedDate = null,
        public readonly DateTimeImmutable|null $createdDate = null,
        public readonly DateTimeImmutable|null $updatedDate = null,
        public readonly int|null $id = null,
        public readonly int|null $adminId = null,
    ) {
    }

    public static function reconstruct(
        int $id,
        int $adminId,
        string $emailAddress,
        DateTimeImmutable|null $verifiedDate,
        DateTimeImmutable $createdDate,
        DateTimeImmutable $updatedDate,
    ): self {
        return new self(
            $emailAddress,
            $verifiedDate,
            $createdDate,
            $updatedDate,
            $id,
            $adminId,
        );
    }

    public function verified(): self
    {
        return new self(
            $this->emailAddress,
            new DateTimeImmutable(),
            $this->createdDate,
            $this->updatedDate,
            $this->id,
            $this->adminId,
        );
    }
}
