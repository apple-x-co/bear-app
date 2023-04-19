<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fieldset;

use MyVendor\MyProject\Form\ExtendedFieldset;

/**
 * @property array{name: string, type: string, tmp_name: string, error: int, size: int}|null $file
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FileFieldset extends ExtendedFieldset
{
    public function init(): void
    {
        $this->setField('file', 'file');
        $this->setField('clientFileName', 'hidden');
        $this->setField('clientMediaType', 'hidden');
        $this->setField('size', 'hidden');
        $this->setField('tmpName', 'hidden');
    }
}
