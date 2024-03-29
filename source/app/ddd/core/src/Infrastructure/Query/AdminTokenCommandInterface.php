<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminTokenCommandInterface
{
    #[DbQuery('admin_token_add')]
    public function add(int $adminId, string $token, DateTimeImmutable $expireAt, ?DateTimeImmutable $createdAt = null): void;
}
