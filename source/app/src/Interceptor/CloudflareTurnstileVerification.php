<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\Captcha\CaptchaException;
use AppCore\Domain\Captcha\CloudflareTurnstileVerificationHandlerInterface;
use MyVendor\MyProject\Annotation\CloudflareTurnstile;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;
use function call_user_func_array;

readonly class CloudflareTurnstileVerification implements MethodInterceptor
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private CloudflareTurnstileVerificationHandlerInterface $cloudflareTurnstileVerificationHandler,
    ) {
    }

    /** @SuppressWarnings(PHPMD.Superglobals) */
    public function invoke(MethodInvocation $invocation): mixed
    {
        $cloudflareTurnstile = $invocation->getMethod()->getAnnotation(CloudflareTurnstile::class);
        assert($cloudflareTurnstile instanceof CloudflareTurnstile);

        try {
            ($this->cloudflareTurnstileVerificationHandler)();
        } catch (CaptchaException $captchaException) {
            $args = $invocation->getArguments()->getArrayCopy();
            $args[] = $captchaException;

            return call_user_func_array(
                [$invocation->getThis(), $cloudflareTurnstile->onFailure],
                $args,
            );
        }

        return $invocation->proceed();
    }
}
