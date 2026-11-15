<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

final readonly class DeleteAdminEmailInputData
{
    public function __construct(
        public int $adminId,
        public int $adminEmailId,
    ) {
    }
}
