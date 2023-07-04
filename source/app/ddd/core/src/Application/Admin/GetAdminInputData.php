<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

class GetAdminInputData
{
    public function __construct(
        public readonly int $adminId
    ) {
    }
}
