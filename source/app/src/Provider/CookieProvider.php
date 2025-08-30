<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use Ray\Di\ProviderInterface;

/** @template-implements ProviderInterface<array<string, string>> */
readonly class CookieProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function get()
    {
        return $_COOKIE;
    }
}
