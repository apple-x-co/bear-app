<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Admin;

use Ray\InputQuery\Attribute\Input;

readonly class FieldsetDemoInput
{
    /**
     * @param array{zip?: string|null, state?: string|null, city?: string|null, street?: string|null, houseType?: string|null, smartphones?: array<string>|null}       $home
     * @param list<array{zip?: string|null, state?: string|null, city?: string|null, street?: string|null, houseType?: string|null, smartphones?: array<string>|null}> $deliveries
     *
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[Input] public string $note,
        #[Input] public string $agree,
        #[Input] public string $__csrf_token, // phpcs:ignore
        #[Input] public string|null $submit,
        #[Input] public array $home = [],
        #[Input] public array $deliveries = [],
    ) {
    }
}
