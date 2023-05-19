<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\EncrypterInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\ResetPassword\ResetPasswordSignature;
use AppCore\Domain\SecureRandomInterface;
use DateTimeImmutable;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Input\Admin\ForgotPasswordInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class ForgotPassword extends AdminPage
{
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly EncrypterInterface $encrypter,
        #[Named('admin_forgot_password_form')] protected readonly FormInterface $form,
        private readonly SecureRandomInterface $secureRandom,
        #[Named('SMTP')] private readonly TransportInterface $transport,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    /**
     * @FormValidation()
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function onPost(ForgotPasswordInput $forgotPassword): static
    {
        $resetPasswordSignature = new ResetPasswordSignature(
            $this->secureRandom->randomBytes(5),
            (new DateTimeImmutable())->modify('+10 minutes'),
            $forgotPassword->emailAddress,
        );
        $encrypted = $this->encrypter->encrypt($resetPasswordSignature->serialize());
        $code = $this->secureRandom->randomNumbers(12);
        $admin = $this->adminRepository->findByEmailAddress($resetPasswordSignature->address);

        if ($admin !== null) {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->adminAddress)
                    ->setTo([new Address($forgotPassword->emailAddress)])
                    ->setTemplate('admin_password_forgot')
                    ->setTemplateVars([
                        'displayName' => $admin->displayName,
                        'expiresAt' => $resetPasswordSignature->expiresAt,
                        'code' => $code,
                    ])
            );
        }

        $this->session->set('admin:password:code', (string) $code);

        $this->renderer = null;
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => (string) $this->router->generate('/admin/reset-password', ['signature' => $encrypted])]; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
