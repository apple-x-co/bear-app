<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;

#[Cacheable(expirySecond: 300)]
class Index extends ResourceObject
{
    /** @var array{greeting: string} */
    public $body;

    /** @return static */
    public function onGet(string $name = 'BEAR.Sunday'): static
    {
        $this->body = [
            'greeting' => 'Hello ' . $name . ' (app)',
        ];

        return $this;
    }
}
