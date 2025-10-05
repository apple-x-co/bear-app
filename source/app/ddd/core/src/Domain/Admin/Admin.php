<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use DateTimeImmutable;

use function array_reduce;
use function array_values;

class Admin
{
    /** @var positive-int|null */
    private int|null $newId = null;

    /**
     * @param list<AdminEmail>  $emails
     * @param positive-int|null $id
     */
    public function __construct(
        public readonly string $username,
        public readonly string $password,
        public readonly string $displayName,
        public readonly bool $active,
        public readonly array $emails,
        public readonly DateTimeImmutable|null $createdDate = null,
        public readonly DateTimeImmutable|null $updatedDate = null,
        public readonly int|null $id = null,
    ) {
    }

    /**
     * @param positive-int|null $id
     * @param list<AdminEmail>  $emails
     */
    public static function reconstruct(
        int|null $id,
        string $username,
        string $password,
        string $displayName,
        bool $active,
        array $emails,
        DateTimeImmutable|null $createdDate,
        DateTimeImmutable|null $updatedDate,
    ): self {
        return new self(
            $username,
            $password,
            $displayName,
            $active,
            $emails,
            $createdDate,
            $updatedDate,
            $id,
        );
    }

    public function markEmailAsVerified(string $emailAddress): self
    {
        $emails = array_values(
            array_reduce(
                $this->emails,
                static function (array $carry, AdminEmail $item) use ($emailAddress) {
                    $carry[] = $item->emailAddress === $emailAddress ? $item->verified() : clone $item;

                    return $carry;
                },
                [],
            ),
        );

        return new self(
            $this->username,
            $this->password,
            $this->displayName,
            $this->active,
            $emails,
            $this->createdDate === null ? null : clone $this->createdDate,
            $this->updatedDate === null ? null : clone $this->updatedDate,
            $this->id,
        );
    }

    public function addEmail(AdminEmail $adminEmail): self
    {
        $emails = array_values(
            array_reduce(
                $this->emails,
                static function (array $carry, AdminEmail $item) {
                    $carry[] = clone $item;

                    return $carry;
                },
                [],
            ),
        );
        $emails[] = $adminEmail;

        return new self(
            $this->username,
            $this->password,
            $this->displayName,
            $this->active,
            $emails,
            $this->createdDate === null ? null : clone $this->createdDate,
            $this->updatedDate === null ? null : clone $this->updatedDate,
            $this->id,
        );
    }

    public function removeEmail(AdminEmail $adminEmail): self
    {
        $emails = array_values(
            array_reduce(
                $this->emails,
                static function (array $carry, AdminEmail $item) use ($adminEmail) {
                    $clone = clone $item;
                    if ($clone->id !== null && $clone->id === $adminEmail->id) {
                        $clone->setRemoval(true);
                    }

                    $carry[] = $clone;

                    return $carry;
                },
                [],
            ),
        );

        return new self(
            $this->username,
            $this->password,
            $this->displayName,
            $this->active,
            $emails,
            $this->createdDate === null ? null : clone $this->createdDate,
            $this->updatedDate === null ? null : clone $this->updatedDate,
            $this->id,
        );
    }

    /** @return positive-int|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param positive-int|null $newId */
    public function setNewId(int|null $newId): void
    {
        $this->newId = $newId;
    }
}
