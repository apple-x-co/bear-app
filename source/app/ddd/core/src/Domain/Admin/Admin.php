<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use DateTimeImmutable;

class Admin
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
        public readonly bool $active,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?DateTimeImmutable $updatedAt = null,
        public readonly ?int $id = null,
    ) {
    }

    public static function reconstruct(
        int $id,
        string $username,
        string $password,
        bool $active,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): Admin {
        return new self(
            $username,
            $password,
            $active,
            $createdAt,
            $updatedAt,
            $id,
        );
    }
}
