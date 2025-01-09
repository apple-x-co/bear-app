<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\Admin;

use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\CacheControl;
use Koriym\HttpConstants\ResponseHeader;

class Index extends ResourceObject
{
    /** @var array<string, string> */
    public $headers = [ResponseHeader::CACHE_CONTROL => CacheControl::PUBLIC_ . ',max-age=86400'];

    public function onGet(): static
    {
        $this->body['HELLO'] = 'Admin';

        return $this;
    }
}
