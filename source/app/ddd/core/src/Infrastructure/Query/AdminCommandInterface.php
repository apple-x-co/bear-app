<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminCommandInterface
{
    /** @return array{id: positive-int} */
    #[DbQuery('admins/admin_add', 'row')]
    public function add(
        string $username,
        string $password,
        string $displayName,
        int $active,
        DateTimeImmutable|null $createdDate = null,
        DateTimeImmutable|null $updatedDate = null,
    ): array;

    #[DbQuery('admins/admin_update')]
    public function update(
        int $id,
        string $username,
        string $displayName,
        int $active,
        DateTimeImmutable|null $updatedDate = null,
    ): void;
}
