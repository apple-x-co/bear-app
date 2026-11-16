<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** @SuppressWarnings("PHPMD.Superglobals") */
class ClientIpResolverTest extends TestCase
{
    /** @var array<string, mixed> */
    private array $originalServer;

    protected function setUp(): void
    {
        $this->originalServer = $_SERVER;
        unset(
            $_SERVER['HTTP_CLOUDFRONT_VIEWER_ADDRESS'],
            $_SERVER['HTTP_CF_CONNECTING_IP'],
            $_SERVER['HTTP_X_FORWARDED_FOR'],
            $_SERVER['REMOTE_ADDR'],
        );
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->originalServer;
    }

    /** @return array<string, array{CdnProvider, array<string, string>, string|null}> */
    public static function provideResolve(): array
    {
        return [
            'amazon: IPv4 のポートを落とす' => [
                CdnProvider::Amazon,
                ['HTTP_CLOUDFRONT_VIEWER_ADDRESS' => '198.51.100.10:46532', 'HTTP_X_FORWARDED_FOR' => '203.0.113.1'],
                '198.51.100.10',
            ],
            'amazon: IPv6 のポートを落とす' => [
                CdnProvider::Amazon,
                ['HTTP_CLOUDFRONT_VIEWER_ADDRESS' => '2001:db8::1:46532'],
                '2001:db8::1',
            ],
            'amazon: 角かっこ付きの IPv6 は角かっことポートを落とす' => [
                CdnProvider::Amazon,
                ['HTTP_CLOUDFRONT_VIEWER_ADDRESS' => '[2001:db8::1]:46532'],
                '2001:db8::1',
            ],
            'amazon: 偽造した CF-Connecting-IP は見ない' => [
                CdnProvider::Amazon,
                ['HTTP_CLOUDFRONT_VIEWER_ADDRESS' => '198.51.100.10:46532', 'HTTP_CF_CONNECTING_IP' => '192.0.2.99'],
                '198.51.100.10',
            ],
            'amazon: ヘッダーが無ければ X-Forwarded-For の最後' => [
                CdnProvider::Amazon,
                ['HTTP_X_FORWARDED_FOR' => '192.0.2.99, 203.0.113.1'],
                '203.0.113.1',
            ],
            'cloudflare: CF-Connecting-IP を使う' => [
                CdnProvider::Cloudflare,
                ['HTTP_CF_CONNECTING_IP' => '198.51.100.20', 'HTTP_X_FORWARDED_FOR' => '203.0.113.1'],
                '198.51.100.20',
            ],
            'cloudflare: 偽造した CloudFront-Viewer-Address は見ない' => [
                CdnProvider::Cloudflare,
                ['HTTP_CLOUDFRONT_VIEWER_ADDRESS' => '192.0.2.99:1234', 'HTTP_CF_CONNECTING_IP' => '198.51.100.20'],
                '198.51.100.20',
            ],
            'none: 偽造した CDN のヘッダーは見ない' => [
                CdnProvider::None,
                [
                    'HTTP_CLOUDFRONT_VIEWER_ADDRESS' => '192.0.2.99:1234',
                    'HTTP_CF_CONNECTING_IP' => '192.0.2.98',
                    'HTTP_X_FORWARDED_FOR' => '192.0.2.97, 203.0.113.1',
                ],
                '203.0.113.1',
            ],
            'none: X-Forwarded-For が無ければ REMOTE_ADDR' => [
                CdnProvider::None,
                ['REMOTE_ADDR' => '203.0.113.2'],
                '203.0.113.2',
            ],
            'none: どれも無ければ null' => [
                CdnProvider::None,
                [],
                null,
            ],
        ];
    }

    /** @param array<string, string> $server */
    #[DataProvider('provideResolve')]
    public function testResolve(CdnProvider $cdnProvider, array $server, string|null $expected): void
    {
        foreach ($server as $key => $value) {
            $_SERVER[$key] = $value;
        }

        $resolver = new ClientIpResolver($cdnProvider);

        $this->assertSame($expected, $resolver->resolve());
    }
}
