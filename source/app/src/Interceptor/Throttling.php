<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\Throttle\ThrottlingHandlerInterface;
use BEAR\Resource\Exception\BadRequestException;
use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Annotation\RateLimiter;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;
use function hash;
use function strrchr;
use function substr;
use function trim;

readonly class Throttling implements MethodInterceptor
{
    private const int TOO_MANY_REQUESTS = 429;

    public function __construct(
        private ThrottlingHandlerInterface $throttle,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $rateLimiter = $invocation->getMethod()->getAnnotation(RateLimiter::class);
        assert($rateLimiter instanceof RateLimiter);

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $clientIp = $this->getClientIp();
        $uri = "{$ro->uri->scheme}://{$ro->uri->host}{$ro->uri->path}";
        $throttleKey = hash('sha3-256', $uri . '|' . $clientIp);

        // 判定の前に数える（先に判定すると、同時に来たリクエストがすべて判定をすり抜ける）
        if ($this->throttle->countUp($throttleKey, $clientIp, $rateLimiter->interval, $rateLimiter->limit)) {
            $ro->view = '';
            $ro->body = [];
            $ro->code = self::TOO_MANY_REQUESTS;
            $ro->headers = [];

            throw new BadRequestException('Too many requests', self::TOO_MANY_REQUESTS, null);
        }

        return $invocation->proceed();
    }

    /**
     * X-Forwarded-For は、クライアントが付けた値の後ろに、ALB が本当のクライアントの IP を足す。
     * クライアントが偽造できる先頭ではなく、ALB が足した最後の 1 つを使う
     * （ALB の前に CloudFront などを置くときは、最後の 1 つが変わるので見直す）
     *
     * @SuppressWarnings("PHPMD.Superglobals")
     */
    private function getClientIp(): string
    {
        $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        if ($forwardedFor === null) {
            return $_SERVER['REMOTE_ADDR'] ?? '';
        }

        return trim(substr(strrchr(',' . $forwardedFor, ','), 1));
    }
}
