<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Admin;

use MyVendor\MyProject\Form\ExtendedForm;
use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminMultipleDemoForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    public function init(): void
    {
        $this->setField('fruits', 'checkbox')
             ->setOptions([
                 '1' => 'Apple',
                 '2' => 'Orange',
                 '3' => 'Grape',
             ]);

        $this->setField('primaryLanguage', 'select')
             ->setOptions([
                 'ja' => 'Japanese',
                 'en' => 'English',
             ]);

        $this->setField('languages', 'select')
            ->setAttribs(['multiple' => true])
             ->setOptions([
                 'ja' => 'Japanese',
                 'en' => 'English',
             ]);

        $this->setField('programmingLanguages', 'select')
             ->setAttribs(['multiple' => true])
             ->setOptions([
                 'backend' => [
                     'php' => 'PHP',
                     'perl' => 'Perl',
                 ],
                 'frontend' => [
                     'js' => 'JavaScript',
                     'css' => 'CSS',
                 ],
             ]);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('submit', 'submit');
    }
}
