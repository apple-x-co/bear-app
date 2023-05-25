<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Annotation\RateLimiter;
use MyVendor\MyProject\Interceptor\Throttling;
use MyVendor\MyProject\Throttle\Throttle;
use MyVendor\MyProject\Throttle\ThrottleInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class ThrottlingModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(ThrottleInterface::class)->to(Throttle::class)->in(Scope::SINGLETON);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(ResourceObject::class),
            $this->matcher->annotatedWith(RateLimiter::class),
            [Throttling::class],
        );
    }
}
