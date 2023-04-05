<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\User;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onGet(): static
    {
        $this->body['HELLO'] = 'UserApi';

        return $this;
    }
}
