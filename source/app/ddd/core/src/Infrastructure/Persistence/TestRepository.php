<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Pagination;
use AppCore\Domain\Test\Test;
use AppCore\Domain\Test\TestNotFoundException;
use AppCore\Domain\Test\TestRepositoryInterface;
use AppCore\Infrastructure\Entity\TestEntity;
use AppCore\Infrastructure\Query\TestCommandInterface;
use AppCore\Infrastructure\Query\TestQueryInterface;

readonly class TestRepository implements TestRepositoryInterface
{
    public function __construct(
        private TestQueryInterface $query,
        private TestCommandInterface $command,
    ) {
    }

    public function findById(string $id): Test
    {
        $entity = $this->query->item($id);
        if ($entity === null) {
            throw new TestNotFoundException($id);
        }

        return $this->entityToModel($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $entities = $this->query->list();

        $tests = [];
        foreach ($entities as $entity) {
            $tests[] = $this->entityToModel($entity);
        }

        return $tests;
    }

    public function pagination(): Pagination
    {
        $pages = $this->query->pagination(2);

        return new Pagination($pages);
    }

    public function store(Test $test): void
    {
        $this->command->add(
            $test->id,
            $test->title,
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function entityToModel(TestEntity $entity): Test
    {
        return Test::reconstruct(
            $entity->id,
            $entity->title,
            $entity->dateCreated,
        );
    }
}
