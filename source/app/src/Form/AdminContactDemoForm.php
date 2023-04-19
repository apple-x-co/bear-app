<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/**
 * @property string $mode
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminContactDemoForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'contactDemo';

    protected function getFormName(): string
    {
        return self::FORM_NAME;
    }

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('username', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'email',
                 'placeholder' => 'username',
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        $this->filter->validate('username')->is('alnum');
        $this->filter->useFieldMessage('username', 'Name must be alphabetic only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('mode', 'submit')
             ->setAttribs(['tabindex' => 2]);
    }
}
