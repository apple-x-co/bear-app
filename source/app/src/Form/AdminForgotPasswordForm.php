<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use AppCore\Infrastructure\Query\AdminQueryInterface;
use Ray\Di\Di\Inject;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminForgotPasswordForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private AdminQueryInterface $adminQuery;

    #[Inject]
    public function setAdminQuery(AdminQueryInterface $adminQuery): void
    {
        $this->adminQuery = $adminQuery;
    }

    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('emailAddress', 'email')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'email',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
                 'title' => '有効なメールアドレスを入力してください',
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('emailAddress')
            ->is('email')
            ->is('callback', function (stdClass $subject, string $field) {
                return $this->adminQuery->itemByEmailAddress($subject->$field) !== null;
            });
        $this->filter->useFieldMessage('emailAddress', '有効なメールアドレスを入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 3]);
    }
}
