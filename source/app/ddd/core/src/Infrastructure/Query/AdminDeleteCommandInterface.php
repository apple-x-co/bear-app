<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminDeleteCommandInterface
{
    #[DbQuery('admin_delete_add')]
    public function add(
        int $adminId,
        DateTimeImmutable $requestAt,
        DateTimeImmutable $scheduleAt,
        ?DateTimeImmutable $createdAt = null,
    ): void;

    #[DbQuery('admin_delete_delete')]
    public function delete(int $adminId, DateTimeImmutable $deletedAt): void;
}
