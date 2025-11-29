<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
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
        DateTimeInterface|null $signupDate,
        DateTimeInterface|null $leavedDate,
        DateTimeInterface|null $purgeDate,
        DateTimeInterface|null $lastLoggedInDate,
        DateTimeInterface|null $createdDate = null,
        DateTimeInterface|null $updatedDate = null,
    ): array;

    #[DbQuery('users/user_update')]
    public function update(
        int $id,
        string $username,
        string $password,
        int $active,
        DateTimeInterface|null $signupDate,
        DateTimeInterface|null $leavedDate,
        DateTimeInterface|null $purgeDate,
        DateTimeInterface|null $lastLoggedInDate,
        DateTimeInterface|null $updatedDate = null,
    ): void;

    #[DbQuery('users/user_delete')]
    public function delete(int $id): void;
}
