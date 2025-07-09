<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\CacheControl;
use Koriym\HttpConstants\ResponseHeader;
use MyVendor\MyProject\InputQuery\IndexInput;
use Ray\InputQuery\Attribute\Input;

class Index extends ResourceObject
{
    /** @var array{greeting: string} */
    public $body;

    /** @var array<string, string> */
    public $headers = [ResponseHeader::CACHE_CONTROL => CacheControl::PUBLIC_ . ',max-age=300'];

    /** @return static */
    public function onGet(#[Input] IndexInput $input): static
    {
        $this->body = [
            'greeting' => 'Hello ' . $input->name . ' (page)',
        ];

        return $this;
    }
}
