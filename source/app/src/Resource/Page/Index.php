<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\InputQuery\IndexInput;
use Ray\InputQuery\Attribute\Input;

#[Cacheable(expirySecond: 300)]
class Index extends ResourceObject
{
    /** @var array{greeting: string} */
    public $body;

    /** @return static */
    public function onGet(
        #[Input]
        IndexInput $input,
    ): static {
        $this->body = [
            'greeting' => 'Hello ' . $input->name . ' (page)',
        ];

        return $this;
    }
}
