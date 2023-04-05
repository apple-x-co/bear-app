<?php

declare(strict_types=1);

namespace AppCore\Domain\Test;

use AppCore\Domain\Pagination;

interface TestRepositoryInterface
{
    public function findById(string $id): Test;

    /** @return array<Test> */
    public function findAll(): array;

    public function pagination(): Pagination;

    public function store(Test $test): void;
}
