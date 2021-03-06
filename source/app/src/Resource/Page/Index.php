<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\Annotation\Embed;
use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Annotation\BenchMark;
use MyVendor\MyProject\MyLoggerInterface;

class Index extends ResourceObject
{
    /** @var array{greeting: string} */
    public $body;

    /**
     * @Embed(rel="index", src="app://self/index{?name}")
     */
    public function onGet(string $name = 'BEAR.Sunday'): static
    {
        $this->body += [
            'name' => $name,
        ];

        return $this;
    }
}
