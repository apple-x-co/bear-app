<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\Admin;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onGet(): static
    {
        $this->body['HELLO'] = 'AdminApi';

        return $this;
    }
}
