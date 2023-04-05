<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\AdminCli;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onPost(): static
    {
        $this->body['HELLO'] = 'AdminCli';

        return $this;
    }
}
