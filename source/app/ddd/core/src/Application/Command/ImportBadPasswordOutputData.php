<?php

declare(strict_types=1);

namespace AppCore\Application\Command;

final class ImportBadPasswordOutputData
{
    /** @param list<string> $passwords */
    public function __construct(
        public array $passwords,
    ) {
    }
}
