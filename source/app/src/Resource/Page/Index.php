<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\MyLoggerInterface;

class Index extends ResourceObject
{
    /** @var array{greeting: string} */
    public $body;

    public function __construct(private MyLoggerInterface $logger)
    {
    }

    /**
     * @return static
     */
    public function onGet(string $name = 'BEAR.Sunday'): static
    {
        $this->body = [
            'greeting' => 'Hello ' . $name,
        ];

        $this->logger->log($name);

        return $this;
    }
}
