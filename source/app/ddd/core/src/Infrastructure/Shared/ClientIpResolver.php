<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Http\ClientIpResolverInterface;

use function strrchr;
use function substr;
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
    public function resolve(): string|null
    {
        $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        if ($forwardedFor === null) {
            return $_SERVER['REMOTE_ADDR'] ?? null;
        }

        return trim(substr(strrchr(',' . $forwardedFor, ','), 1));
    }
}
