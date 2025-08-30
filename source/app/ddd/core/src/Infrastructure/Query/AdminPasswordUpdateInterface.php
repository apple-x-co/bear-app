<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;
use SensitiveParameter;

interface AdminPasswordUpdateInterface
{
    #[DbQuery('admins/admin_password_update')]
    public function __invoke(
        int $id,
        #[SensitiveParameter]
        string $password,
    ): void;
}
