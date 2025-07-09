<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery;

use Ray\InputQuery\Attribute\Input;

final readonly class IndexInput
{
    public function __construct(
        #[Input] public string $name = 'BEAR.Sunday',
    ) {
    }
}
