<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\TestEntity;
use AppCore\Infrastructure\Entity\TestEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;
use Ray\MediaQuery\Annotation\Pager;
use Ray\MediaQuery\Pages;

interface TestQueryInterface
{
    #[DbQuery('test_item', type: 'row', factory: TestEntityFactory::class)]
    public function item(string $id): ?TestEntity;

    /** @return array<TestEntity> */
    #[DbQuery('test_list', factory: TestEntityFactory::class)]
    public function list(): array;

    #[DbQuery('test_list', factory: TestEntityFactory::class), Pager(perPage: 'pageNum', template: '/{?page}')]
    public function pagination(int $pageNum): Pages;
}
