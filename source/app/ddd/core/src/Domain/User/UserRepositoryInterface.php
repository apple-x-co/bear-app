<?php

declare(strict_types=1);

namespace AppCore\Domain\User;

use DateTimeImmutable;

interface UserRepositoryInterface
{
    public function findById(int $id): User;

    /**
     * @param list<positive-int> $ids
     *
     * @return list<User>
     */
    public function findByIds(array $ids): array;

    public function findByUsername(string $username): User;

    /** @return list<User> */
    public function findByExpired(DateTimeImmutable $dateTime): array;

    public function store(User $user): void;

    public function delete(User $user): void;
}
