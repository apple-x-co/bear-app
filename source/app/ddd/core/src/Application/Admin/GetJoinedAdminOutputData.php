<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

final readonly class GetJoinedAdminOutputData
{
    public function __construct(
        public string $signature,
    ) {
    }
}
