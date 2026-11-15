<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

final readonly class DeleteAdminInputData
{
    public function __construct(
        public int $adminId,
    ) {
    }
}
