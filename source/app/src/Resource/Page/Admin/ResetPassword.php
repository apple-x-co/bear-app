<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\EncrypterInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\PasswordHasherInterface;
use AppCore\Domain\ResetPassword\ExpiredSignatureException;
use AppCore\Domain\ResetPassword\ResetPasswordSignature;
use AppCore\Infrastructure\Query\AdminPasswordUpdateInterface;
use DateTimeImmutable;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Input\Admin\ResetPasswordInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Annotation\WriteConnection;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

use function assert;
use function urldecode;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class ResetPassword extends AdminPage
{
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly AdminPasswordUpdateInterface $adminPasswordUpdate,
        private readonly EncrypterInterface $encrypter,
        #[Named('admin_password_reset_form')] protected readonly FormInterface $form,
        private readonly PasswordHasherInterface $passwordHasher,
        #[Named('SMTP')] private readonly TransportInterface $transport,
    ) {
        $this->body['form'] = $this->form;
    }

    /**
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    #[WriteConnection]
    #[Transactional]
    public function onGet(string $signature): static
    {
        $decrypted = $this->encrypter->decrypt(urldecode($signature));
        $resetPasswordSignature = ResetPasswordSignature::deserialize($decrypted);

        $now = new DateTimeImmutable();
        if ($resetPasswordSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        assert($this->form instanceof ExtendedForm);
        $this->form->fill(['signature' => $signature]);

        return $this;
    }

    /**
     * @FormValidation()
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function onPost(ResetPasswordInput $resetPassword): static
    {
        $decrypted = $this->encrypter->decrypt(urldecode($resetPassword->signature));
        $resetPasswordSignature = ResetPasswordSignature::deserialize($decrypted);

        $now = new DateTimeImmutable();
        if ($resetPasswordSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        $code = $this->session->get('admin:password:code');
        $this->session->remove('admin:password:code');

        if ($resetPassword->code !== $code) {
            $this->renderer = null;
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/admin/login']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

            return $this;
        }

        $admin = $this->adminRepository->findByEmailAddress($resetPasswordSignature->address);
        if ($admin === null || $admin->id === null) {
            $this->renderer = null;
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/admin/login']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

            return $this;
        }

        ($this->adminPasswordUpdate)($admin->id, $this->passwordHasher->hash($resetPassword->password));
        $this->transport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($resetPasswordSignature->address)])
                ->setTemplate('admin_password_reset')
                ->setTemplateVars(['displayName' => $admin->displayName])
        );

        $this->renderer = null;
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/login']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
