<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface BadPasswordCommandInterface
{
    #[DbQuery('bad_password_add')]
    public function add(string $password, ?DateTimeImmutable $createdAt = null): void;
}
