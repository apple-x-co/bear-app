<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Session\SessionInterface;
use Aura\Session\Segment;

readonly class Session implements SessionInterface
{
    public function __construct(
        private Segment $segment,
    ) {
    }

    public function set(string $key, string $val): void
    {
        $this->segment->set($key, $val);
    }

    public function get(string $key, string|null $alt = null): mixed
    {
        return $this->segment->get($key, $alt);
    }

    public function reset(string $key): void
    {
        $this->segment->set($key, null);
    }
}
