<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

use Aura\Input\Builder;
use MyVendor\MyProject\Form\Customer\Fieldset\AddressFieldset;
use MyVendor\MyProject\Form\Customer\Fieldset\AddressFilter;
use MyVendor\MyProject\Form\ExtendedForm;
use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserFieldsetDemoForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    public function init(): void
    {
        $this->useFieldsets([
            'address' => static function () {
                return new AddressFieldset(
                    new Builder(),
                    new AddressFilter(),
                );
            },
        ]);

        $this->setFieldset('home', 'address');

        $this->setCollection('deliveries', 'address');

        $this->setField('note', 'textarea');

        $this->setField('agree', 'checkbox')
             ->setAttribs([
                 'value' => 'yes',
                 'label' => 'YES',
                 'value_unchecked' => 'no',
             ]);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit');
    }
}
