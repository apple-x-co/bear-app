<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminDeleteCommandInterface
{
    #[DbQuery('admins/admin_delete_add')]
    public function add(
        int $adminId,
        DateTimeInterface $requestDate,
        DateTimeInterface $scheduleDate,
        DateTimeInterface|null $createdDate = null,
    ): void;

    #[DbQuery('admins/admin_delete_delete')]
    public function delete(int $adminId, DateTimeInterface $deletedDate): void;
}
