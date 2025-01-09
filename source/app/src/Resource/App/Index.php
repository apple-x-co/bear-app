<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App;

use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\CacheControl;
use Koriym\HttpConstants\ResponseHeader;

class Index extends ResourceObject
{
    public $headers = [ResponseHeader::CACHE_CONTROL => CacheControl::PUBLIC_ . ',max-age=300'];

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
