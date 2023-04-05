<?php

declare(strict_types=1);

namespace AppCore\Domain\Test;

use DateTimeImmutable;

readonly class Test
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly ?DateTimeImmutable $dateCreated = null,
    ) {
    }

    public static function reconstruct(
        string $id,
        string $title,
        DateTimeImmutable $dateCreated,
    ): Test {
        return new self(
            $id,
            $title,
            $dateCreated,
        );
    }
}
