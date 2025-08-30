<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface TestCommandInterface
{
    #[DbQuery('test/test_add')]
    public function add(string $id, string $title, DateTimeImmutable|null $dateCreated = null): void;
}
