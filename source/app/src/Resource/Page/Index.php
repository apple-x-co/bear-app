<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\CacheControl;
use Koriym\HttpConstants\ResponseHeader;

class Index extends ResourceObject
{
    /** @var array{greeting: string} */
    public $body;

    /** @var array<string, string> */
    public $headers = [ResponseHeader::CACHE_CONTROL => CacheControl::PUBLIC_ . ',max-age=300'];

    /** @return static */
    public function onGet(string $name = 'BEAR.Sunday'): static
    {
        $this->body = [
            'greeting' => 'Hello ' . $name . ' (page)',
        ];

        return $this;
    }
}
