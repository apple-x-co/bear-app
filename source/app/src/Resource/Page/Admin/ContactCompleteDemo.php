<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use MyVendor\MyProject\Resource\Page\AdminPage;

class ContactCompleteDemo extends AdminPage
{
    public function onGet(): static
    {
        return $this;
    }
}
