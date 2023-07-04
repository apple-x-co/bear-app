<?php

declare(strict_types=1);

namespace AppCore\Application;

class GetVerificationCodeInputData
{
    public function __construct(
        public readonly string $uuid,
    ) {
    }
}
