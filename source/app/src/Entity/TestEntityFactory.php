<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Entity;

use DateTimeImmutable;

class TestEntityFactory
{
    public static function factory(string $id, string $name, string $dateCreated): TestEntity
    {
        return new TestEntity($id, $name, new DateTimeImmutable($dateCreated));
    }
}
