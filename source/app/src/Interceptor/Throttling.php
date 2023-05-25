<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use BEAR\Resource\Exception\BadRequestException;
use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Annotation\RateLimiter;
use MyVendor\MyProject\Throttle\ThrottleInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;
use function sha1;

class Throttling implements MethodInterceptor
{
    private const TOO_MANY_REQUESTS = 429;

    public function __construct(
        private readonly ThrottleInterface $throttle
    ) {
    }

    /** @SuppressWarnings(PHPMD.Superglobals) */
    public function invoke(MethodInvocation $invocation): mixed
    {
        $rateLimiter = $invocation->getMethod()->getAnnotation(RateLimiter::class);
        assert($rateLimiter instanceof RateLimiter);

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $remoteIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        $uri = "{$ro->uri->scheme}://{$ro->uri->host}{$ro->uri->path}";
        $key = sha1($uri . '|' . $remoteIp);

        if ($this->throttle->isExceeded($key)) {
            $ro->view = null;
            $ro->code = self::TOO_MANY_REQUESTS;
            $ro->headers = [];

            throw new BadRequestException('Too many requests', self::TOO_MANY_REQUESTS, null);
        }

        $this->throttle->countUp($key, $remoteIp, $rateLimiter->interval, $rateLimiter->limit);

        return $invocation->proceed();
    }
}
