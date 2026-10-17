<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fixture;

use Aura\Input\Builder;
use Aura\Input\Fieldset;

/** @psalm-suppress PropertyNotSetInConstructor */
class NestedDemoAddressFieldset extends Fieldset
{
    public function init(): void
    {
        $this->builder = new Builder([
            'geo' => static fn () => new NestedDemoGeoFieldset(new Builder(), new NestedDemoGeoFilter()),
        ]);

        $this->setField('zip', 'text');
        $this->setFieldset('geo');
    }
}
