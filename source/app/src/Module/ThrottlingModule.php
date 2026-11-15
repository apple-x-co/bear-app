<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Domain\Throttle\ThrottlingHandlerInterface;
use AppCore\Infrastructure\Shared\ThrottlingHandler;
use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Annotation\RateLimiter;
use MyVendor\MyProject\Interceptor\Throttling;
use Override;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

final class ThrottlingModule extends AbstractModule
{
    #[Override]
    protected function configure(): void
    {
        $this->bind(ThrottlingHandlerInterface::class)->to(ThrottlingHandler::class)->in(Scope::SINGLETON);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(ResourceObject::class),
            $this->matcher->annotatedWith(RateLimiter::class),
            [Throttling::class],
        );
    }
}
