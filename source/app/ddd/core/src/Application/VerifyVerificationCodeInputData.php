<?php

declare(strict_types=1);

namespace AppCore\Application;

final class VerifyVerificationCodeInputData
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $code,
    ) {
    }
}
