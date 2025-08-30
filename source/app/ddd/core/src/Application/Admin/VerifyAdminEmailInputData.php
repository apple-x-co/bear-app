<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

readonly class VerifyAdminEmailInputData
{
    public function __construct(
        public int $adminId,
        public string $signature,
    ) {
    }
}
