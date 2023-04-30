<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ThrottleRemoveByKeyInterface
{
    #[DbQuery('throttle_remove_by_key')]
    public function __invoke(string $throttleKey): void;
}
