<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\AdminEmailEntity;
use AppCore\Infrastructure\Entity\AdminEmailEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminEmailQueryInterface
{
    /** @return list<AdminEmailEntity> */
    #[DbQuery('admins/admin_email_by_admin_id', factory: AdminEmailEntityFactory::class)]
    public function list(int $adminId): array;
}
