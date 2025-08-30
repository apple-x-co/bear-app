<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminDeleteCommandInterface
{
    #[DbQuery('admins/admin_delete_add')]
    public function add(
        int $adminId,
        DateTimeImmutable $requestDate,
        DateTimeImmutable $scheduleDate,
        DateTimeImmutable|null $createdDate = null,
    ): void;

    #[DbQuery('admins/admin_delete_delete')]
    public function delete(int $adminId, DateTimeImmutable $deletedDate): void;
}
