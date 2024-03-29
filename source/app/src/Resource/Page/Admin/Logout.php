<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use BEAR\Resource\NullRenderer;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\AdminLogout;
use MyVendor\MyProject\Resource\Page\AdminPage;

class Logout extends AdminPage
{
    #[AdminGuard]
    #[AdminLogout]
    public function onPost(): static
    {
        $this->renderer = new NullRenderer();

        return $this;
    }
}
