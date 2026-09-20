<?php

declare(strict_types=1);

namespace AppCore\Domain\Uri;

use Psr\Http\Message\UriInterface;

interface PublicStaticUriBuilderInterface
{
    /** @param array<string, mixed> $params */
    public function build(string $path, array $params = []): UriInterface;
}
