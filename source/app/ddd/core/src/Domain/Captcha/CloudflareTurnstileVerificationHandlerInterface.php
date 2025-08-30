<?php

declare(strict_types=1);

namespace AppCore\Domain\Captcha;

/** @SuppressWarnings(PHPMD.LongClassName) */
interface CloudflareTurnstileVerificationHandlerInterface
{
    public function __invoke(): void;
}
