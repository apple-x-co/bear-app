<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use AppCore\Infrastructure\Query\AdminQueryInterface;
use Ray\Di\Di\Inject;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminSignUpForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'signUp';

    private AdminQueryInterface $adminQuery;

    #[Inject]
    public function setAdminQuery(AdminQueryInterface $adminQuery): void
    {
        $this->adminQuery = $adminQuery;
    }

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('displayName', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => '',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        $this->filter->validate('displayName')->is('string');
        $this->filter->useFieldMessage('displayName', 'Display name must be string.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('username', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => '',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 2,
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('username')
            ->is('string')
            ->is('callback', function (stdClass $subject, string $field) {
                return $this->adminQuery->itemByUsername($subject->$field) === null;
            });
        $this->filter->useFieldMessage('username', 'Username must be string.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 3,
             ]);
        $this->filter->validate('password')->is('alnum');
        $this->filter->useFieldMessage('password', 'Name must be alphabetic only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('signature', 'hidden');
        $this->filter->validate('signature')->isNotBlank()->is('string');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 4]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
