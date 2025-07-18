<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminUploadDemoForm extends ExtendedForm
{
    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('file', 'file')
             ->setAttribs(['required' => 'required']);

        // NOTE: $_POSTS[] にアップロードファイルは含まれないのでここでは検証しない

        /** @psalm-suppress UndefinedMethod */
        $this->setField('submit', 'submit');
    }

    public function getFormName(): string
    {
        return '';
    }
}
