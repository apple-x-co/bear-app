<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Admin\Fieldset;

use Ray\InputQuery\Attribute\Input;

final readonly class Address
{
    /** @param list<string>|null $smartphones */
    public function __construct(
        #[Input] public string|null $zip = null,
        #[Input] public string|null $state = null,
        #[Input] public string|null $city = null,
        #[Input] public string|null $street = null,
        #[Input] public string|null $houseType = null,
        #[Input] public array|null $smartphones = null,
    ) {
    }
}
