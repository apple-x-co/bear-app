<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface AdminTokenRemoveByAdminIdInterface
{
    #[DbQuery('admin_token_remove_by_admin_id')]
    public function __invoke(int $adminId): void;
}
