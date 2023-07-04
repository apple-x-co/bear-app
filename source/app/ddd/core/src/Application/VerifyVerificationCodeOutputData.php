<?php

declare(strict_types=1);

namespace AppCore\Application;

class VerifyVerificationCodeOutputData
{
    public function __construct(
        public readonly string $url,
    ) {
    }
}
