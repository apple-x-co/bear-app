<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Admin;

use Ray\InputQuery\Attribute\Input;

final readonly class DeleteEmailInput
{
    /** @param int<1, max> $id */
    public function __construct(
        #[Input] public int $id,
    ) {
    }
}
