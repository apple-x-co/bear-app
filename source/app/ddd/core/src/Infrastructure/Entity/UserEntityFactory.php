<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final class UserEntityFactory
{
    /**
     * @param positive-int $id
     * @param int<0, 1>    $active
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function factory(
        int $id,
        string $uid,
        string $username,
        string $password,
        int $active,
        string $signupDate,
        string|null $leavedDate,
        string|null $purgeDate,
        string|null $lastLoggedInDate,
        string $createdDate,
        string $updatedDate,
    ): UserEntity {
        return new UserEntity(
            $id,
            $uid,
            $username,
            $password,
            $active === 1,
            new DateTimeImmutable($signupDate),
            $leavedDate === null ? null : new DateTimeImmutable($leavedDate),
            $purgeDate === null ? null : new DateTimeImmutable($purgeDate),
            $lastLoggedInDate === null ? null : new DateTimeImmutable($lastLoggedInDate),
            new DateTimeImmutable($createdDate),
            new DateTimeImmutable($updatedDate),
        );
    }
}
