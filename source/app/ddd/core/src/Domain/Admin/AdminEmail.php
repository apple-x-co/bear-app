<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use DateTimeImmutable;

class AdminEmail
{
    public function __construct(
        public readonly int $adminId,
        public readonly string $emailAddress,
        public readonly ?DateTimeImmutable $verifiedAt,
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
    ): AdminEmail {
        return new self(
            $adminId,
            $emailAddress,
            $verifiedAt,
            $createdAt,
            $updatedAt,
            $id,
        );
    }
}
