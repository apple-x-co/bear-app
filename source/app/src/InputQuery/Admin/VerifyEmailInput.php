<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Admin;

use Ray\InputQuery\Attribute\Input;

final readonly class VerifyEmailInput
{
    public function __construct(
        #[Input] public string $signature,
    ) {
    }
}
