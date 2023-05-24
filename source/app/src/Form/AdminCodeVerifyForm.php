<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminCodeVerifyForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'codeVerify';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        $this->setField('uuid', 'hidden');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('code', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => '',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        $this->filter->validate('code')->is('int');
        $this->filter->useFieldMessage('code', 'Code must be integer only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 2]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
