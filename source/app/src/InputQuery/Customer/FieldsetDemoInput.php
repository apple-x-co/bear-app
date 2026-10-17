<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Customer;

use MyVendor\MyProject\InputQuery\Customer\Fieldset\Address;
use Ray\InputQuery\Attribute\Input;

readonly class FieldsetDemoInput
{
    /**
     * @param list<Address>|null $deliveries
     *
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings("PHPMD.CamelCaseParameterName")
     */
    public function __construct(
        #[Input]
        public string $note,
        #[Input]
        public string $agree,
        #[Input]
        public string $__csrf_token, // phpcs:ignore
        #[Input]
        public string|null $continue,
        #[Input]
        public Address|null $home = null,
        #[Input(item: Address::class)]
        public array|null $deliveries = [],
    ) {
    }
}
