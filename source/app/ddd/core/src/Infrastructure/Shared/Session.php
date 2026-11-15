<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Session\SessionInterface;
use Aura\Session\Segment;
use Override;

final readonly class Session implements SessionInterface
{
    public function __construct(
        private Segment $segment,
    ) {
    }

    #[Override]
    public function set(string $key, string $val): void
    {
        $this->segment->set($key, $val);
    }

    #[Override]
    public function get(string $key, string|null $alt = null): mixed
    {
        return $this->segment->get($key, $alt);
    }

    #[Override]
    public function reset(string $key): void
    {
        $this->segment->set($key, null);
    }
}
