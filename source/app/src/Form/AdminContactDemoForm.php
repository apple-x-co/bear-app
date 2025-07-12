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

    protected function getFormName(): string
    {
        return '';
    }

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
        $this->setField('mode', 'submit');
    }
}
