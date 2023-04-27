<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminTokenCommandInterface
{
    #[DbQuery('admin_tokens_add')]
    public function add(int $adminId, string $token, DateTimeImmutable $expireAt, ?DateTimeImmutable $createdAt = null): void;
}
