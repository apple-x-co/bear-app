<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface TestCommandInterface
{
    #[DbQuery('test_add')]
    public function add(string $id, string $title, ?DateTimeImmutable $dateCreated = null): void;
}
