<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\Cli;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onPost(): static
    {
        $this->body['HELLO'] = 'Cli';

        return $this;
    }
}
