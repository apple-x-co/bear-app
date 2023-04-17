<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fieldset;

use MyVendor\MyProject\Form\ExtendedFieldset;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AddressFieldset extends ExtendedFieldset
{
    public function init(): void
    {
        $this->setField('zip', 'text')
             ->setAttribs(['required' => 'required']);
        $this->setField('state', 'text');
        $this->setField('city', 'text');
        $this->setField('street', 'text');
        $this->setField('houseType', 'radio')
             ->setOptions([
                 '1' => 'Single-family',
                 '2' => 'Townhouse',
             ]);
        $this->setField('smartphones', 'select')
             ->setAttribs(['multiple' => true])
             ->setOptions([
                 'iphone15' => 'iPhone 15',
                 'iphone14' => 'iPhone 14',
                 'iphone13' => 'iPhone 13',
                 'iphone12' => 'iPhone 12',
                 'iphone11' => 'iPhone 11',
                 'iphoneX' => 'iPhone X',
             ]);
    }
}
