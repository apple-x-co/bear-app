<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fixture;

use Aura\Input\Fieldset;

/** @psalm-suppress PropertyNotSetInConstructor */
class NestedDemoGeoFieldset extends Fieldset
{
    public function init(): void
    {
        $this->setField('lat', 'text');
    }
}
