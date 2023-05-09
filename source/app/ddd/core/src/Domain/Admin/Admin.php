<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use DateTimeImmutable;

class Admin
{
    /**
     * @param array<AdminEmail> $emails
     */
    public function __construct(
        public readonly string $username,
        public readonly string $password,
        public readonly string $displayName,
        public readonly bool $active,
        public readonly array $emails,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?DateTimeImmutable $updatedAt = null,
        public readonly ?int $id = null,
    ) {
    }

    /**
     * @param array<AdminEmail> $emails
     */
    public static function reconstruct(
        int $id,
        string $username,
        string $password,
        string $displayName,
        bool $active,
        array $emails,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): Admin {
        return new self(
            $username,
            $password,
            $displayName,
            $active,
            $emails,
            $createdAt,
            $updatedAt,
            $id,
        );
    }
}
