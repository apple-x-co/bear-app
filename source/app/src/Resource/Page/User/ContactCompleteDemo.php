<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\User;

use BEAR\RepositoryModule\Annotation\NoHttpCache;
use MyVendor\MyProject\Resource\Page\BaseUserPage;

#[NoHttpCache]
class ContactCompleteDemo extends BaseUserPage
{
    public function onGet(): static
    {
        return $this;
    }
}
