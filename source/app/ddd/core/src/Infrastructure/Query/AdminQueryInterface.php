<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\AdminEntity;
use AppCore\Infrastructure\Entity\AdminEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminQueryInterface
{
    #[DbQuery('admin_item', type: 'row', factory: AdminEntityFactory::class)]
    public function item(int $id): ?AdminEntity;
}
