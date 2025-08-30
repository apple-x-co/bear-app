<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

readonly class CreateAdminEmailInputData
{
    /** @param positive-int $adminId */
    public function __construct(
        public int $adminId,
        public string $emailAddress,
    ) {
    }
}
