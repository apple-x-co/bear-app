<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Query;

use MyVendor\MyProject\Entity\ThrottleEntity;
use MyVendor\MyProject\Entity\ThrottleEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface ThrottleQueryInterface
{
    #[DbQuery('throttle_item_by_key', type: 'row', factory: ThrottleEntityFactory::class)]
    public function itemByKey(string $throttleKey): ?ThrottleEntity;
}
