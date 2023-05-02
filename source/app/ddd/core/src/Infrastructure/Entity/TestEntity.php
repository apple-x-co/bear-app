<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;
use Ray\MediaQuery\CamelCaseTrait;

class TestEntity
{
    use CamelCaseTrait;

    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly DateTimeImmutable $dateCreated,
    ) {
    }
}
