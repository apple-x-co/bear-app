<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use AppCore\Infrastructure\Query\BadPasswordQueryInterface;
use Ray\Di\Di\Inject;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminPasswordResetForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'resetPassword';

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
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 2,
                 'minlength' => 8,
                 'maxlength' => 20,
                 'pattern' => '^[\x20-\x7E]+$', // ASCII 文字列
                 'title' => '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください',
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('password')
            ->is('alnum')
            ->is('strlenBetween', 8, 20)
            ->is('callback', function (stdClass $subject, string $field) {
                return $this->badPasswordQuery->item($subject->$field) === null;
            });
        $this->filter->useFieldMessage('password', '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('signature', 'hidden');
        $this->filter->validate('signature')->isNotBlank()->is('string');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 3]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
