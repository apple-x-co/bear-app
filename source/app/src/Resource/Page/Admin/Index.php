<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use BEAR\RepositoryModule\Annotation\NoHttpCache;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Resource\Page\BaseAdminPage;

#[NoHttpCache]
class Index extends BaseAdminPage
{
    #[AdminGuard]
    public function onGet(): static
    {
        return $this;
    }
}
