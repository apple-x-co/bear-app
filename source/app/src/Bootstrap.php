<?php

declare(strict_types=1);

namespace MyVendor\MyProject;

use AppCore\Domain\Locale\Locale;
use BEAR\Resource\ResourceObject;
use BEAR\Resource\Uri;
use BEAR\Sunday\Extension\Application\AppInterface;
use BEAR\Sunday\Extension\Router\RouterInterface;
use MyVendor\MyProject\Module\App;
use Throwable;

use function assert;

/**
 * @psalm-import-type Globals from RouterInterface
 * @psalm-import-type Server from RouterInterface
 */
final class Bootstrap
{
    /**
     * @param Globals $globals
     * @param Server  $server
     *
     * @return 0|1
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function __invoke(string $context, array $globals, array $server): int
    {
        $app = Injector::getInstance($context)->getInstance(AppInterface::class);
        assert($app instanceof App);
        if ($app->httpCache->isNotModified($server)) {
            $app->httpCache->transfer();

            return 0;
        }

        $request = $app->router->match($globals, $server);
        try {
            // BEAR\Resource\Uri でリソース URI をパースし、スキームとホストを動的に取得する
            // 例: http://localhost/ja/topics/detail → page://self/ja/topics/detail
            //     $uri->scheme = 'page', $uri->host = 'self', $uri->path = '/ja/topics/detail'
            $uri = new Uri($request->path);
            $uriPath = Locale::stripPrefixFromPath($uri->path);

            $response = $app->resource->{$request->method}->uri("{$uri->scheme}://{$uri->host}{$uriPath}")($request->query);
            assert($response instanceof ResourceObject);
            $response->transfer($app->responder, $server);

            return 0;
        } catch (Throwable $e) {
            $app->throwableHandler->handle($e, $request)->transfer();

            return 1;
        }
    }
}
