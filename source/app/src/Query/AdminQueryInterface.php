<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Query;

use MyVendor\MyProject\Entity\AdminEntity;
use MyVendor\MyProject\Entity\AdminEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminQueryInterface
{
    #[DbQuery('admin_item', type: 'row', factory: AdminEntityFactory::class)]
    public function item(int $id): ?AdminEntity;
}
