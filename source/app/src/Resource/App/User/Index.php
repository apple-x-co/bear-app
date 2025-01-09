<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\User;

use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\CacheControl;
use Koriym\HttpConstants\ResponseHeader;

class Index extends ResourceObject
{
    public $headers = [ResponseHeader::CACHE_CONTROL => CacheControl::PUBLIC_ . ',max-age=86400'];

    public function onGet(): static
    {
        $this->body['HELLO'] = 'User';

        return $this;
    }
}
