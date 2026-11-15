<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use Override;
use Ray\Di\ProviderInterface;

/** @template-implements ProviderInterface<array<string, string>> */
final readonly class CookieProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings("PHPMD.Superglobals")
     */
    #[Override]
    public function get()
    {
        /** @var array<string, string> $cookies */
        $cookies = $_COOKIE;

        return $cookies;
    }
}
