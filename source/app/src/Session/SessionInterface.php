<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Session;

interface SessionInterface
{
    public function set(string $key, string $val): void;

    public function get(string $key, ?string $alt = null): mixed;

    public function setFlashMessage(string $val): void;

    public function getFlashMessage(?string $alt = null): mixed;
}
