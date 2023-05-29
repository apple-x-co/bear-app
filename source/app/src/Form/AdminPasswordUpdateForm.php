<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use AppCore\Infrastructure\Query\BadPasswordQueryInterface;
use Ray\Di\Di\Inject;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminPasswordUpdateForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'updatePassword';

    private BadPasswordQueryInterface $badPasswordQuery;

    #[Inject]
    public function setBadPasswordQuery(BadPasswordQueryInterface $badPasswordQuery): void
    {
        $this->badPasswordQuery = $badPasswordQuery;
    }

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('oldPassword', 'password')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'current-password',
                 'id' => 'current-password',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
                 'title' => '現在のパスワードを入力してください',
             ]);
        $this->filter
            ->validate('oldPassword')
            ->isNotBlank()
            ->is('alnum');
        $this->filter->useFieldMessage('oldPassword', '現在のパスワードは文字で入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'new-password',
                 'id' => 'new-password',
                 'minlength' => 8,
                 'maxlength' => 20,
                 'pattern' => '^[\x20-\x7E]+$', // ASCII 文字列
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 2,
                 'title' => '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください',
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('password')
            ->isNotBlank()
            ->is('alnum')
            ->is('strlenBetween', 8, 20)
            ->isNot('equalToField', 'oldPassword')
            ->is('callback', function (stdClass $subject, string $field) {
                return $this->badPasswordQuery->item($subject->$field) === null;
            });
        $this->filter->useFieldMessage('password', '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('passwordConfirmation', 'password')
             ->setAttribs([
                 'autocomplete' => 'new-password',
                 'minlength' => 8,
                 'maxlength' => 20,
                 'pattern' => '^[\x20-\x7E]+$', // ASCII 文字列
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 3,
                 'title' => '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください',
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('passwordConfirmation')
            ->isNotBlank()
            ->is('alnum')
            ->is('strlenBetween', 8, 20)
            ->is('equalToField', 'password');
        $this->filter->useFieldMessage('passwordConfirmation', '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('update', 'submit')
             ->setAttribs(['tabindex' => 4]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
