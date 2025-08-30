<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\AdminTokenEntity;
use AppCore\Infrastructure\Entity\AdminTokenEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminTokenQueryInterface
{
    #[DbQuery('admins/admin_token_item_by_token', type: 'row', factory: AdminTokenEntityFactory::class)]
    public function itemByToken(string $token): AdminTokenEntity|null;
}
