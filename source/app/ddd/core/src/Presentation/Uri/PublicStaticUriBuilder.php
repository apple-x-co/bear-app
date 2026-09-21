<?php

declare(strict_types=1);

namespace AppCore\Presentation\Uri;

use AppCore\Attribute\PublicStaticBaseUrl;
use AppCore\Domain\Uri\PublicStaticUriBuilderInterface;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

use function http_build_query;

final readonly class PublicStaticUriBuilder implements PublicStaticUriBuilderInterface
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        #[PublicStaticBaseUrl]
        private string $publicStaticBaseUrl,
    ) {
    }

    /** @inheritDoc */
    public function build(string $path, array $params = []): UriInterface
    {
        // Aura.Router は現状必要ない

        $query = empty($params) ? '' : '?' . http_build_query($params);

        return new Uri($this->publicStaticBaseUrl . $path . $query);
    }
}
