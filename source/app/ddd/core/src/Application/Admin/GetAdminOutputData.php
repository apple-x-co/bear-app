<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Admin\Admin;

readonly class GetAdminOutputData
{
    public function __construct(public Admin $admin)
    {
    }
}
