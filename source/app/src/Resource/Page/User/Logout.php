<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\User;

use BEAR\Resource\NullRenderer;
use MyVendor\MyProject\Annotation\UserGuard;
use MyVendor\MyProject\Annotation\UserLogout;
use MyVendor\MyProject\Resource\Page\UserPage;

class Logout extends UserPage
{
    #[UserGuard]
    #[UserLogout]
    public function onGet(): static
    {
        $this->renderer = new NullRenderer();

        return $this;
    }
}
