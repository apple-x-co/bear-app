<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

class AdminMultipleDemoForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'multipleDemo';

    protected function getFormName(): string
    {
        return self::FORM_NAME;
    }

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        $this->setField('fruits', 'checkbox')
             ->setAttribs([
//                 'value_unchecked' => '',
             ])
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
            ->setAttribs([
                'multiple' => true,
            ])
             ->setOptions([
                 'ja' => 'Japanese',
                 'en' => 'English',
             ]);

        $this->setField('programmingLanguages', 'select')
             ->setAttribs([
                 'multiple' => true,
             ])
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

        $this->setField('agree', 'checkbox')
             ->setAttribs([
                 'value' => 'yes',
                 'label' => 'YES',
                 'value_unchecked' => 'no',
             ]);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('submit', 'submit')
             ->setAttribs(['tabindex' => 99]);
    }
}
