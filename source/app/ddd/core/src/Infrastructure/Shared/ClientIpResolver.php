<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Http\ClientIpResolverInterface;
use Override;

use function end;
use function explode;
use function strrpos;
use function substr;
use function trim;

final class ClientIpResolver implements ClientIpResolverInterface
{
    public function __construct(
        private CdnProvider $cdnProvider,
    ) {
    }

    /**
     * クライアントの IP を、設定した CDN（CDN_PROVIDER_NAME）に合わせて判定する
     *
     * - amazon: CloudFront が付ける CloudFront-Viewer-Address（「IP:ポート」なのでポートを落とす）を使う
     * - cloudflare: Cloudflare が付ける CF-Connecting-IP を使う
     * - none: CDN のヘッダーは見ない（クライアントが偽造できるため）
     *
     * CDN のヘッダーが無いときは X-Forwarded-For を見る。X-Forwarded-For は、クライアントが付けた値の後ろに
     * ALB が本当の接続元の IP を足すので、偽造できる先頭ではなく、ALB が足した最後の 1 つを使う。
     * どちらも無いときは REMOTE_ADDR を使う
     *
     * CDN を経由せずに ALB へ直接アクセスされると CDN のヘッダーも偽造できる。
     * ALB の Security Group を CloudFront のマネージドプレフィックスリストや Cloudflare の IP レンジに絞ること
     *
     * @SuppressWarnings("PHPMD.Superglobals")
     */
    #[Override]
    public function resolve(): string|null
    {
        $cdnIp = match ($this->cdnProvider) {
            CdnProvider::Amazon => $this->stripPort($_SERVER['HTTP_CLOUDFRONT_VIEWER_ADDRESS'] ?? null),
            CdnProvider::Cloudflare => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null,
            CdnProvider::None => null,
        };
        if ($cdnIp !== null) {
            return $cdnIp;
        }

        $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        if ($forwardedFor !== null) {
            $ips = explode(',', $forwardedFor);

            return trim(end($ips));
        }

        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    /**
     * CloudFront-Viewer-Address は「IP:ポート」なので、最後の : から後ろを落とす
     * （IPv6 は角かっこなしで届くが、公式ドキュメントに明記がないため [IPv6]:ポート の形でも角かっこを外す）
     */
    private function stripPort(string|null $address): string|null
    {
        if ($address === null) {
            return null;
        }

        $pos = strrpos($address, ':');

        return $pos === false ? $address : trim(substr($address, 0, $pos), '[]');
    }
}
