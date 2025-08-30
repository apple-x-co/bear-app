<?php

declare(strict_types=1);

namespace AppCore\Domain\Session;

interface SessionInterface
{
    public function set(string $key, string $val): void;

    public function get(string $key, string|null $alt = null): mixed;

    public function reset(string $key): void;
}
