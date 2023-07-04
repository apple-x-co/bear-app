<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

class JoinAdminInputData
{
    public function __construct(
        public readonly string $emailAddress
    ) {
    }
}
