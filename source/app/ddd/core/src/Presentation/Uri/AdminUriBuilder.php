<?php

declare(strict_types=1);

namespace AppCore\Presentation\Uri;

use AppCore\Attribute\AdminBaseUrl;
use AppCore\Domain\Uri\AdminUriBuilderInterface;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

use function http_build_query;

final readonly class AdminUriBuilder implements AdminUriBuilderInterface
{
    public function __construct(
        #[AdminBaseUrl]
        private string $adminBaseUrl,
    ) {
    }

    /** @inheritDoc */
    public function build(string $path, array $params = []): UriInterface
    {
        // Aura.Router は現状必要ない

        $query = empty($params) ? '' : '?' . http_build_query($params);

        return new Uri($this->adminBaseUrl . $path . $query);
    }
}
