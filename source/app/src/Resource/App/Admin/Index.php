<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\Admin;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;

#[Cacheable(expirySecond: 86400)]
class Index extends ResourceObject
{
    public function onGet(): static
    {
        $this->body['HELLO'] = 'Admin';

        return $this;
    }
}
