<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Input\Collection;

use function is_array;

/**
 * 不正な POST でも例外にならない Collection
 *
 * 標準の Collection::fill() は未知のキーで NoSuchInput、配列でない行で Warning になる。
 * Fieldset::fill() は未知のキーを無視するので、行ごとに委譲する。
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ExtendedCollection extends Collection
{
    /** @param array<array-key, mixed> $data */
    public function fill(array $data): void
    {
        $this->fieldsets = [];
        foreach ($data as $key => $row) {
            if (! is_array($row)) {
                continue;
            }

            $fieldset = $this->newFieldset($key);
            $fieldset->fill($row);
            $this->fieldsets[$key] = $fieldset;
        }
    }
}
