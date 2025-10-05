<?php

declare(strict_types=1);

namespace AppCore\Domain\User;

use DateInterval;
use DateTimeImmutable;

final class User
{
    /** @var positive-int|null */
    private int|null $newId = null;

    /**
     * @param positive-int|null $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly string $uid,
        public readonly string $username,
        public readonly string $password,
        public readonly bool $active,
        public readonly DateTimeImmutable $signupDate,
        public readonly DateTimeImmutable|null $leavedDate = null,
        public readonly DateTimeImmutable|null $purgeDate = null,
        public readonly DateTimeImmutable|null $lastLoggedInDate = null,
        public readonly DateTimeImmutable|null $createdDate = null,
        public readonly DateTimeImmutable|null $updatedDate = null,
        public readonly int|null $id = null,
    ) {
    }

    /**
     * @param positive-int $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        string $uid,
        string $username,
        string $password,
        bool $active,
        DateTimeImmutable $signupDate,
        DateTimeImmutable|null $leavedDate,
        DateTimeImmutable|null $purgeDate,
        DateTimeImmutable|null $lastLoggedInDate,
        DateTimeImmutable $createdDate,
        DateTimeImmutable $updatedDate,
    ): self {
        return new self(
            $uid,
            $username,
            $password,
            $active,
            $signupDate,
            $leavedDate,
            $purgeDate,
            $lastLoggedInDate,
            $createdDate,
            $updatedDate,
            $id,
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

    public function withUsername(string $username, DateTimeImmutable $updatedDate): self
    {
        return new self(
            $this->uid,
            $username,
            $this->password,
            $this->active,
            $this->signupDate,
            $this->leavedDate === null ? null : clone $this->leavedDate,
            $this->purgeDate === null ? null : clone $this->purgeDate,
            $this->lastLoggedInDate === null ? null : clone $this->lastLoggedInDate,
            $this->createdDate,
            $updatedDate,
            $this->id,
        );
    }

    public function withPassword(string $password, DateTimeImmutable $updatedDate): self
    {
        return new self(
            $this->uid,
            $this->username,
            $password,
            $this->active,
            $this->signupDate,
            $this->leavedDate === null ? null : clone $this->leavedDate,
            $this->purgeDate === null ? null : clone $this->purgeDate,
            $this->lastLoggedInDate === null ? null : clone $this->lastLoggedInDate,
            $this->createdDate,
            $updatedDate,
            $this->id,
        );
    }

    public function withLeave(DateTimeImmutable $leavedDate): self
    {
        $purgeDate = $leavedDate->add(new DateInterval('P10D'));

        return new self(
            $this->uid,
            $this->username,
            $this->password,
            false,
            $this->signupDate,
            $leavedDate,
            $purgeDate,
            $this->lastLoggedInDate,
            $this->createdDate,
            $this->updatedDate,
            $this->id,
        );
    }

    public function withLoggedIn(DateTimeImmutable $loggedInDate): self
    {
        return new self(
            $this->uid,
            $this->username,
            $this->password,
            $this->active,
            $this->signupDate,
            $this->leavedDate,
            $this->purgeDate,
            $loggedInDate,
            $this->createdDate,
            $this->updatedDate,
            $this->id,
        );
    }
}
