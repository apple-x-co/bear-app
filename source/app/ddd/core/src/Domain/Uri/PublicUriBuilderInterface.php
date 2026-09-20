<?php

declare(strict_types=1);

namespace AppCore\Domain\Uri;

use AppCore\Domain\Locale\Locale;
use Psr\Http\Message\UriInterface;

interface PublicUriBuilderInterface
{
    /** @param array<string, mixed> $params */
    public function build(string $path, array $params = [], Locale|null $locale = null): UriInterface;
}
