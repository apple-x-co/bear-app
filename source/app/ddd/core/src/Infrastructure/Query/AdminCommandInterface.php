<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
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
        DateTimeInterface|null $createdDate = null,
        DateTimeInterface|null $updatedDate = null,
    ): array;

    #[DbQuery('admins/admin_update')]
    public function update(
        int $id,
        string $username,
        string $displayName,
        int $active,
        DateTimeInterface|null $updatedDate = null,
    ): void;
}
