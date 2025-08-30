<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminEmailCommandInterface
{
    /** @return array{id: int} */
    #[DbQuery('admins/admin_email_add', 'row')]
    public function add(
        int $adminId,
        string $emailAddress,
        DateTimeImmutable|null $createdDate = null,
        DateTimeImmutable|null $updatedDate = null,
    ): array;

    #[DbQuery('admins/admin_email_verified')]
    public function verified(int $id, DateTimeImmutable $verifiedDate, DateTimeImmutable|null $updatedDate = null): void;

    #[DbQuery('admins/admin_email_delete')]
    public function delete(int $id): void;
}
