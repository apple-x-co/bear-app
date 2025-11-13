<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Attribute\CloudflareTurnstileSecretKey;
use AppCore\Attribute\CloudflareTurnstileSiteKey;
use AppCore\Domain\Captcha\CloudflareTurnstileVerificationHandlerInterface;
use AppCore\Infrastructure\Shared\CloudflareTurnstileVerificationHandler;
use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Annotation\CloudflareTurnstile;
use MyVendor\MyProject\Interceptor\CloudflareTurnstileVerification;
use Ray\Di\AbstractModule;

use function getenv;

/** @SuppressWarnings("PHPMD.CouplingBetweenObjects") */
class CaptchaModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind()->annotatedWith(CloudflareTurnstileSiteKey::class)->toInstance((string) getenv('CLOUDFLARE_TURNSTILE_SITE_KEY'));
        $this->bind()->annotatedWith(CloudflareTurnstileSecretKey::class)->toInstance((string) getenv('CLOUDFLARE_TURNSTILE_SECRET_KEY'));

        $this->bind(CloudflareTurnstileVerificationHandlerInterface::class)->to(CloudflareTurnstileVerificationHandler::class);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(ResourceObject::class),
            $this->matcher->annotatedWith(CloudflareTurnstile::class),
            [CloudflareTurnstileVerification::class],
        );
    }
}
