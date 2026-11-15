<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use Override;
use Ray\Di\ProviderInterface;

/** @template-implements ProviderInterface<array<string, string>> */
readonly class CookieProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings("PHPMD.Superglobals")
     */
    #[Override]
    public function get()
    {
        return $_COOKIE;
    }
}
