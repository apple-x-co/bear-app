<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fixture;

use Aura\Input\Builder;
use MyVendor\MyProject\Form\ExtendedForm;

/** @psalm-suppress PropertyNotSetInConstructor */
class NestedDemoForm extends ExtendedForm
{
    public function init(): void
    {
        $this->useFieldsets([
            'address' => static fn () => new NestedDemoAddressFieldset(new Builder(), new NestedDemoZipFilter()),
        ]);

        $this->setField('note', 'textarea');
        /** @psalm-suppress TooManyArguments */
        $this->filter->validate('note')->is('strlenMin', 1);
        $this->filter->useFieldMessage('note', 'Note is required.');

        $this->setFieldset('home', 'address');
        $this->setCollection('deliveries', 'address');
    }
}
