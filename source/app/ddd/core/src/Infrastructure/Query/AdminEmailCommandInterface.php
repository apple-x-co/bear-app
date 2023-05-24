<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminEmailCommandInterface
{
    /**
     * @return array{id: int}
     */
    #[DbQuery('admin_email_add', 'row')]
    public function add(
        int $adminId,
        string $emailAddress,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ): array;

    #[DbQuery('admin_email_verified')]
    public function verified(int $id, DateTimeImmutable $verifiedAt, ?DateTimeImmutable $updatedAt = null): void;

    #[DbQuery('admin_email_delete')]
    public function delete(int $id): void;
}
