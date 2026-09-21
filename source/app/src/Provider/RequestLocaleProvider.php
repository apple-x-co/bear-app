<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Locale\Locale;
use Koriym\HttpConstants\RequestHeader;
use Locale as PhpLocale;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\ProviderInterface;

use function explode;
use function str_contains;

/**
 * Locale を提供
 *
 * @template-implements ProviderInterface<Locale>
 */
final readonly class RequestLocaleProvider implements ProviderInterface
{
    public function __construct(
        private ServerRequestInterface $request,
    ) {
    }

    /**
     * @inheritDoc
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function get()
    {
        $uriPath = $this->request->getUri()->getPath();

        // "/" はクライアント理解できる言語を返す
        if ($uriPath === '/') {
            $acceptLanguageList = $this->request->getHeader(RequestHeader::ACCEPT_LANGUAGE);
            if (empty($acceptLanguageList)) {
                return Locale::Japanese;
            }

            $acceptLanguage = PhpLocale::acceptFromHttp($acceptLanguageList[0]);
            // ex: ja
            // ex: ja,en;q=0.9,en-GB;q=0.8,en-US;q=0.7
            if ($acceptLanguage === false) {
                return Locale::Japanese;
            }

            // 国コード付きの場合は分割
            if (str_contains($acceptLanguage, '_')) {
                $langRegion = explode('_', $acceptLanguage);
                $acceptLanguage = $langRegion[0];
            }

            $acceptLanguageLocale = Locale::tryFrom($acceptLanguage);

            return $acceptLanguageLocale ?? Locale::Japanese;
        }

        // 上記以外はパスの一部から言語を返す
        $requestLocale = Locale::tryFromPath($uriPath);

        return $requestLocale ?? Locale::Japanese;
    }
}
