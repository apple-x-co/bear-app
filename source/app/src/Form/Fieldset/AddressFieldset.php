<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fieldset;

use Aura\Input\Fieldset;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AddressFieldset extends Fieldset
{
    public function init(): void
    {
        $this->setField('zip');
        $this->setField('state');
        $this->setField('city');
        $this->setField('street');
    }
}
