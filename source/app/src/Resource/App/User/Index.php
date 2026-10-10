<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\User;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;

#[Cacheable(expirySecond: 86400)]
class Index extends ResourceObject
{
    public function onGet(): static
    {
        $this->body['HELLO'] = 'User';

        return $this;
    }
}
