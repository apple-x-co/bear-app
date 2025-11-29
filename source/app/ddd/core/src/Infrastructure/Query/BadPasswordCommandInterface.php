<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;

interface BadPasswordCommandInterface
{
    #[DbQuery('bad_passwords/bad_password_add')]
    public function add(string $password, DateTimeInterface|null $createdDate = null): void;
}
