<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Query;

use MyVendor\MyProject\Entity\AdminTokenEntity;
use MyVendor\MyProject\Entity\AdminTokenEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminTokenQueryInterface
{
    #[DbQuery('admin_token_item_by_token', type: 'row', factory: AdminTokenEntityFactory::class)]
    public function itemByToken(string $token): ?AdminTokenEntity;
}
