<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminCommandInterface
{
    /**
     * @return array{id: int}
     */
    #[DbQuery('admin_add', 'row')]
    public function add(
        string $username,
        string $password,
        string $displayName,
        int $active,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ): array;

    #[DbQuery('admin_update')]
    public function update(
        int $id,
        string $username,
        string $displayName,
        int $active,
        ?DateTimeImmutable $updatedAt = null,
    ): void;
}
