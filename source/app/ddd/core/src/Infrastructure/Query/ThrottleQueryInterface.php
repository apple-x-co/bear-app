<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\ThrottleEntity;
use AppCore\Infrastructure\Entity\ThrottleEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface ThrottleQueryInterface
{
    #[DbQuery('throttles/throttle_item_by_key_including_expired', type: 'row', factory: ThrottleEntityFactory::class)]
    public function itemByKeyIncludingExpired(string $throttleKey): ThrottleEntity|null;
}
