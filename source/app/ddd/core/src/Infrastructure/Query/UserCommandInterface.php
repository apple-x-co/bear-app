<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface UserCommandInterface
{
    /**
     * @return array{id: positive-int}
     *
     * @SuppressWarnings("PHPMD.ExcessiveParameterList")
     */
    #[DbQuery('users/user_add', type: 'row')]
    public function add(
        string $uid,
        string $username,
        string $password,
        int $active,
        DateTimeImmutable|null $signupDate,
        DateTimeImmutable|null $leavedDate,
        DateTimeImmutable|null $purgeDate,
        DateTimeImmutable|null $lastLoggedInDate,
        DateTimeImmutable|null $createdDate = null,
        DateTimeImmutable|null $updatedDate = null,
    ): array;

    #[DbQuery('users/user_update')]
    public function update(
        int $id,
        string $username,
        string $password,
        int $active,
        DateTimeImmutable|null $signupDate,
        DateTimeImmutable|null $leavedDate,
        DateTimeImmutable|null $purgeDate,
        DateTimeImmutable|null $lastLoggedInDate,
        DateTimeImmutable|null $updatedDate = null,
    ): void;

    #[DbQuery('users/user_delete')]
    public function delete(int $id): void;
}
