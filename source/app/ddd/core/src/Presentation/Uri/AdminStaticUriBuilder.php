<?php

declare(strict_types=1);

namespace AppCore\Presentation\Uri;

use AppCore\Attribute\AdminStaticBaseUrl;
use AppCore\Domain\Uri\AdminStaticUriBuilderInterface;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

use function http_build_query;

final readonly class AdminStaticUriBuilder implements AdminStaticUriBuilderInterface
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        #[AdminStaticBaseUrl]
        private string $adminStaticBaseUrl,
    ) {
    }

    /** @inheritDoc */
    public function build(string $path, array $params = []): UriInterface
    {
        // Aura.Router は現状必要ない

        $query = empty($params) ? '' : '?' . http_build_query($params);

        return new Uri($this->adminStaticBaseUrl . $path . $query);
    }
}
