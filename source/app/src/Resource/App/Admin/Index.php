<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\Admin;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;

/** @psalm-suppress ClassMustBeFinal BEAR.Sunday の AOP がリソースを継承して織り込むため final にしない */
#[Cacheable(expirySecond: 86400)]
class Index extends ResourceObject
{
    public function onGet(): static
    {
        $this->body['HELLO'] = 'Admin';

        return $this;
    }
}
