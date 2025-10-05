<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings;

use AppCore\Domain\AccessControl\Permission;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\Resource\Page\BaseAdminPage;

class Index extends BaseAdminPage
{
    #[AdminGuard]
    #[RequiredPermission('settings', Permission::Read)]
    public function onGet(): static
    {
        return $this;
    }
}
