<?php

declare(strict_types=1);

namespace AppCore\Presentation\Uri;

use AppCore\Attribute\PublicBaseUrl;
use AppCore\Domain\Locale\Locale;
use AppCore\Domain\Uri\PublicUriBuilderInterface;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

use function http_build_query;

final readonly class PublicUriBuilder implements PublicUriBuilderInterface
{
    public function __construct(
        #[PublicBaseUrl]
        private string $publicBaseUrl,
    ) {
    }

    /** @inheritDoc */
    public function build(string $path, array $params = [], Locale|null $locale = null): UriInterface
    {
        // Aura.Router は現状必要ない

        $query = empty($params) ? '' : '?' . http_build_query($params);

        if ($locale === null) {
            return new Uri($this->publicBaseUrl . $path . $query);
        }

        return new Uri($this->publicBaseUrl . '/' . $locale->value . $path . $query);
    }
}
