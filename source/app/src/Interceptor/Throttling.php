<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\Http\ClientIpResolverInterface;
use AppCore\Domain\Throttle\ThrottlingHandlerInterface;
use BEAR\Resource\Exception\BadRequestException;
use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Annotation\RateLimiter;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;
use function hash;

readonly class Throttling implements MethodInterceptor
{
    private const int TOO_MANY_REQUESTS = 429;

    public function __construct(
        private ClientIpResolverInterface $clientIpResolver,
        private ThrottlingHandlerInterface $throttle,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $rateLimiter = $invocation->getMethod()->getAnnotation(RateLimiter::class);
        assert($rateLimiter instanceof RateLimiter);

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $clientIp = $this->clientIpResolver->resolve() ?? '';
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
}
