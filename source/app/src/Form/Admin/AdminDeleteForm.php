<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Admin;

use MyVendor\MyProject\Form\ExtendedForm;
use Override;
use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminDeleteForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    #[Override]
    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit');
    }
}
