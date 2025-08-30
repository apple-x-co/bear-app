<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface BadPasswordQueryInterface
{
    /** @return array{password: string, created_date: string}|null */
    #[DbQuery('bad_passwords/bad_password_item', type: 'row')]
    public function item(string $password): array|null;
}
