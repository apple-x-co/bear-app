<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final readonly class UserEntity
{
    /**
     * @param positive-int $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public int $id,
        public string $uid,
        public string $username,
        public string $password,
        public bool $active,
        public DateTimeImmutable $signupDate,
        public DateTimeImmutable|null $leavedDate,
        public DateTimeImmutable|null $purgeDate,
        public DateTimeImmutable|null $lastLoggedInDate,
        public DateTimeImmutable $createdDate,
        public DateTimeImmutable $updatedDate,
    ) {
    }
}
