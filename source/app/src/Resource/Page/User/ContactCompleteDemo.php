<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\User;

use BEAR\RepositoryModule\Annotation\NoHttpCache;
use MyVendor\MyProject\Resource\Page\BaseUserPage;

/** @psalm-suppress ClassMustBeFinal BEAR.Sunday の AOP がリソースを継承して織り込むため final にしない */
#[NoHttpCache]
class ContactCompleteDemo extends BaseUserPage
{
    public function onGet(): static
    {
        return $this;
    }
}
