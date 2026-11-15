<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use Override;
use Ray\Di\ProviderInterface;

use function is_string;

/** @template-implements ProviderInterface<array<array-key, string>> */
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
        // `name[key]=value` 形式の Cookie は PHP が配列にするので、文字列の値だけを返す
        $cookies = [];
        foreach ($_COOKIE as $name => $value) {
            /** @psalm-suppress TypeDoesNotContainType psalm は $_COOKIE の値を常に文字列とみなすが、実際は配列も入る */
            if (! is_string($value)) {
                continue;
            }

            $cookies[$name] = $value;
        }

        return $cookies;
    }
}
