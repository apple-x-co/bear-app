<?php

declare(strict_types=1);

namespace AppCore\Domain\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

interface HttpClientInterface
{
    /** @param array<string, mixed> $options */
    public function request(string $method, string|UriInterface $uri = '', array $options = []): ResponseInterface;
}
