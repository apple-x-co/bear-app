<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface AdminDeleteQueryInterface
{
    /** @return list<array{admin_id: int, request_at: string, schedule_at: string, deleted_at: string|null, created_at: string}> */
    #[DbQuery('admins/admin_delete_list')]
    public function list(): array;
}
