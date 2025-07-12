<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use AppCore\Infrastructure\Query\AdminQueryInterface;
use AppCore\Infrastructure\Query\BadPasswordQueryInterface;
use Ray\Di\Di\Inject;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminSignUpForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private AdminQueryInterface $adminQuery;
    private BadPasswordQueryInterface $badPasswordQuery;

    #[Inject]
    public function setAdminQuery(AdminQueryInterface $adminQuery): void
    {
        $this->adminQuery = $adminQuery;
    }

    #[Inject]
    public function setBadPasswordQuery(BadPasswordQueryInterface $badPasswordQuery): void
    {
        $this->badPasswordQuery = $badPasswordQuery;
    }

    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('displayName', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'nickname',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        $this->filter->validate('displayName')->is('string');
        $this->filter->useFieldMessage('displayName', 'Display name must be string.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('username', 'text')
             ->setAttribs([
                 'autocomplete' => 'username',
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
                 'minlength' => 8,
                 'maxlength' => 20,
                 'pattern' => '^[\x20-\x7E]+$', // ASCII 文字列
                 'title' => '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください',
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('password')
            ->is('string')
            ->is('regex', '/^[A-Za-z0-9!@#$%^&*]+$/i')
            ->is('strlenBetween', 8, 20)
            ->isNot('equalToField', 'username')
            ->is('callback', function (stdClass $subject, string $field) {
                return $this->badPasswordQuery->item($subject->$field) === null;
            });
        $this->filter->useFieldMessage('password', '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('signature', 'hidden');
        $this->filter->validate('signature')->isNotBlank()->is('string');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 4]);
    }

    public function getFormName(): string
    {
        return '';
    }
}
