<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Http\ClientIpResolverInterface;
use Override;

use function end;
use function explode;
use function trim;

final class ClientIpResolver implements ClientIpResolverInterface
{
    /**
     * X-Forwarded-For は、クライアントが付けた値の後ろに、ALB が本当のクライアントの IP を足す。
     * クライアントが偽造できる先頭ではなく、ALB が足した最後の 1 つを使う
     * （ALB の前に CloudFront などを置くときは、最後の 1 つが変わるので見直す）
     *
     * @SuppressWarnings("PHPMD.Superglobals")
     */
    #[Override]
    public function resolve(): string|null
    {
        $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        if ($forwardedFor === null) {
            return $_SERVER['REMOTE_ADDR'] ?? null;
        }

        $ips = explode(',', $forwardedFor);

        return trim(end($ips));
    }
}
