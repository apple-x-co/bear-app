<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Http\HttpClientInterface;
use GuzzleHttp\ClientInterface;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final readonly class HttpClient implements HttpClientInterface
{
    public function __construct(
        private ClientInterface $guzzle,
    ) {
    }

    /** @param array<string, mixed> $options */
    #[Override]
    public function request(string $method, UriInterface|string $uri = '', array $options = []): ResponseInterface
    {
        return $this->guzzle->request($method, $uri, $options);
    }
}
