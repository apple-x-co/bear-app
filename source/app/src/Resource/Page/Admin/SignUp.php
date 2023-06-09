<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Domain\Admin\Admin;
use AppCore\Domain\Admin\AdminEmail;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\WebSignature\ExpiredSignatureException;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use BEAR\Resource\NullRenderer;
use DateTimeImmutable;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Input\Admin\SignUpInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;
use Throwable;

use function assert;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class SignUp extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminRepositoryInterface $adminRepository,
        #[Named('admin_sign_up_form')] protected readonly FormInterface $form,
        private readonly PasswordHasherInterface $passwordHasher,
        #[Named('SMTP')] private readonly TransportInterface $smtpTransport,
        #[Named('queue')] private readonly TransportInterface $queueTransport,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(string $signature): static
    {
        try {
            $webSignature = $this->webSignatureEncrypter->decrypt($signature);
        } catch (Throwable) {
            $this->session->set('error:message', 'message:admin:sign_up:decrypt_error');
            $this->session->set('error:returnName', 'Join');
            $this->session->set('error:returnUrl', '/admin/join');
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
     */
    public function onPost(SignUpInput $signUp): static
    {
        $webSignature = $this->webSignatureEncrypter->decrypt($signUp->signature);
        $now = new DateTimeImmutable();
        if ($webSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        $admin = new Admin(
            $signUp->username,
            $this->passwordHasher->hash($signUp->password),
            $signUp->displayName,
            true,
            [new AdminEmail($webSignature->address, new DateTimeImmutable())],
        );
        $this->adminRepository->store($admin);

        $this->queueTransport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($webSignature->address, $signUp->displayName)])
                ->setTemplateId('admin_welcome')
                ->setTemplateVars(['displayName' => $signUp->displayName])
                ->setScheduleAt(new DateTimeImmutable())
        );

        $this->smtpTransport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($webSignature->address, $signUp->displayName)])
                ->setTemplateId('admin_sign_up')
                ->setTemplateVars(['displayName' => $signUp->displayName])
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
