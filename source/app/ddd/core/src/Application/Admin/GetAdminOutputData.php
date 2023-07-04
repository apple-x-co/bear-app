<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Admin\Admin;

class GetAdminOutputData
{
    public function __construct(
        public readonly Admin $admin
    ) {
    }
}
