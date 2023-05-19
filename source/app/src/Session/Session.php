<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Session;

use Aura\Session\Segment;

class Session implements SessionInterface
{
    public function __construct(
        private readonly Segment $segment
    ) {
    }

    public function set(string $key, string $val): void
    {
        $this->segment->set($key, $val);
    }

    public function get(string $key, ?string $alt = null): mixed
    {
        return $this->segment->get($key, $alt);
    }

    public function remove(string $key): void
    {
        $this->segment->set($key, null);
    }

    public function setFlashMessage(string $val): void
    {
        $this->segment->setFlash('message', $val);
    }

    public function getFlashMessage(?string $alt = null): mixed
    {
        return $this->segment->getFlash('message', $alt);
    }
}
