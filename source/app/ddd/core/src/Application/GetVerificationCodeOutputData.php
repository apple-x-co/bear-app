<?php

declare(strict_types=1);

namespace AppCore\Application;

class GetVerificationCodeOutputData
{
    public function __construct(
        public readonly int $id,
        public readonly string $uuid,
        public readonly string $url,
    ) {
    }
}
