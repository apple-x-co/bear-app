<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

class DeleteAdminInputData
{
    public function __construct(
        public readonly int $adminId,
    ) {
    }
}
