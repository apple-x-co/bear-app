<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminPasswordConfirmForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'userPassword';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        $this->filter->validate('password')->is('alnum');
        $this->filter->useFieldMessage('password', 'Name must be alphabetic only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 2]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
