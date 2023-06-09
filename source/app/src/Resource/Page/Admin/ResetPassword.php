<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\WebSignature\ExpiredSignatureException;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use AppCore\Infrastructure\Query\AdminPasswordUpdateInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use BEAR\Resource\NullRenderer;
use DateTimeImmutable;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Input\Admin\ResetPasswordInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;
use Throwable;

use function assert;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class ResetPassword extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminQueryInterface $adminQuery,
        private readonly AdminPasswordUpdateInterface $adminPasswordUpdate,
        #[Named('admin_password_reset_form')] protected readonly FormInterface $form,
        private readonly PasswordHasherInterface $passwordHasher,
        #[Named('SMTP')] private readonly TransportInterface $transport,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
        $this->body['form'] = $this->form;
    }

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function onGet(string $signature): static
    {
        try {
            $webSignature = $this->webSignatureEncrypter->decrypt($signature);
        } catch (Throwable) {
            $this->session->set('error:message', 'message:admin:reset_password:decrypt_error');
            $this->session->set('error:returnName', 'Forgot password');
            $this->session->set('error:returnUrl', '/admin/forgot-password');
            $this->renderer = new NullRenderer();
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/admin/error'];

            return $this;
        }

        $now = new DateTimeImmutable();
        if ($webSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        assert($this->form instanceof ExtendedForm);
        $this->form->fill(['signature' => $signature]);

        return $this;
    }

    /**
     * @FormValidation()
     * @Transactional()
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function onPost(ResetPasswordInput $resetPassword): static
    {
        $webSignature = $this->webSignatureEncrypter->decrypt($resetPassword->signature);
        $now = new DateTimeImmutable();
        if ($webSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        $adminEntity = $this->adminQuery->itemByEmailAddress($webSignature->address);
        if ($adminEntity === null) {
            $this->renderer = new NullRenderer();
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/admin/login']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

            return $this;
        }

        ($this->adminPasswordUpdate)($adminEntity->id, $this->passwordHasher->hash($resetPassword->password));
        $this->transport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($webSignature->address)])
                ->setTemplateId('admin_password_reset')
                ->setTemplateVars(['displayName' => $adminEntity->displayName])
        );

        $this->renderer = new NullRenderer();
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/login']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
