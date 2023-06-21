<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use DateTimeImmutable;

use function array_reduce;

class Admin
{
    private ?int $newId = null;

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
        ?int $id,
        string $username,
        string $password,
        string $displayName,
        bool $active,
        array $emails,
        ?DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt,
    ): self {
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

    public function markEmailAsVerified(string $emailAddress): self
    {
        $emails = array_reduce(
            $this->emails,
            static function (array $carry, AdminEmail $item) use ($emailAddress) {
                $carry[] = $item->emailAddress === $emailAddress ? $item->verified() : clone $item;

                return $carry;
            },
            []
        );

        return self::reconstruct(
            $this->id,
            $this->username,
            $this->password,
            $this->displayName,
            $this->active,
            $emails,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function addEmail(AdminEmail $adminEmail): self
    {
        $emails = array_reduce(
            $this->emails,
            static function (array $carry, AdminEmail $item) {
                $carry[] = clone $item;

                return $carry;
            },
            []
        );
        $emails[] = $adminEmail;

        return self::reconstruct(
            $this->id,
            $this->username,
            $this->password,
            $this->displayName,
            $this->active,
            $emails,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function removeEmail(AdminEmail $adminEmail): self
    {
        $emails = array_reduce(
            $this->emails,
            static function (array $carry, AdminEmail $item) use ($adminEmail) {
                $clone = clone $item;
                if ($clone->id !== null && $clone->id === $adminEmail->id) {
                    $clone->setRemoval(true);
                }

                $carry[] = $clone;

                return $carry;
            },
            []
        );

        return self::reconstruct(
            $this->id,
            $this->username,
            $this->password,
            $this->displayName,
            $this->active,
            $emails,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function getNewId(): ?int
    {
        return $this->newId;
    }

    public function setNewId(?int $newId): void
    {
        $this->newId = $newId;
    }
}
