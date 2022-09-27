<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\Annotation\Embed;
use BEAR\Resource\ResourceObject;

class Form extends ResourceObject
{
    /** @var array */
    public $body;

    public function onGet(): static
    {
        $this->body = [];

        return $this;
    }
}
