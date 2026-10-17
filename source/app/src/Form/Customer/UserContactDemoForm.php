<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Form\FormStep;
use Ray\WebFormModule\SetAntiCsrfTrait;

use function array_column;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserContactDemoForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    /** @SuppressWarnings("PHPMD.StaticAccess") */
    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('username', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'email',
                 'placeholder' => 'username',
                 'required' => 'required',
             ]);
        $this->filter->validate('username')->is('alnum');
        $this->filter->useFieldMessage('username', 'Name must be alphabetic only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('step', 'hidden');
        /** @psalm-suppress TooManyArguments */
        $this->filter->validate('step')->is('inValues', array_column(FormStep::cases(), 'value'));

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit');
    }
}
