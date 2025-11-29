<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminTokenCommandInterface
{
    #[DbQuery('admins/admin_token_add')]
    public function add(int $adminId, string $token, DateTimeInterface $expireDate, DateTimeInterface|null $createdDate = null): void;
}
