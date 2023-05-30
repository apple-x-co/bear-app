<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Domain\WebSignature\WebSignature;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use AppCore\Infrastructure\Query\VerificationCodeCommandInterface;
use DateTimeImmutable;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\GoogleRecaptchaV2;
use MyVendor\MyProject\Annotation\RateLimiter;
use MyVendor\MyProject\Captcha\RecaptchaException;
use MyVendor\MyProject\Input\Admin\JoinInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class Join extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        #[Named('admin_base_url')] private readonly string $adminBaseUrl,
        private readonly AdminQueryInterface $adminQuery,
        #[Named('admin_join_form')] protected readonly FormInterface $form,
        private readonly SecureRandomInterface $secureRandom,
        #[Named('SMTP')] private readonly TransportInterface $transport,
        private readonly VerificationCodeCommandInterface $verificationCodeCommand,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    /**
     * @FormValidation()
     */
    #[GoogleRecaptchaV2]
    #[RateLimiter]
    public function onPost(JoinInput $join): static
    {
        $expiresAt = (new DateTimeImmutable())->modify('+10 minutes');
        $code = $this->secureRandom->randomNumbers(12);

        $adminEntity = $this->adminQuery->itemByEmailAddress($join->emailAddress);
        if ($adminEntity === null) {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->adminAddress)
                    ->setTo([new Address($join->emailAddress)])
                    ->setTemplateId('admin_join')
                    ->setTemplateVars([
                        'expiresAt' => $expiresAt,
                        'code' => $code,
                    ])
            );
        }

        $webSignature = new WebSignature(
            $expiresAt,
            $join->emailAddress,
        );
        $encrypted = $this->webSignatureEncrypter->encrypt($webSignature);

        $array = $this->verificationCodeCommand->add(
            $join->emailAddress,
            $this->adminBaseUrl . (string) $this->router->generate('/admin/sign-up', ['signature' => $encrypted]),
            (string) $code,
            $expiresAt,
        );

        $this->renderer = null;
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => (string) $this->router->generate('/admin/code-verify', ['uuid' => $array['uuid']])]; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }

    /**
     * @param array<RecaptchaException> $recaptchaExceptions
     */
    public function onPostGoogleRecaptchaV2Failed(array $recaptchaExceptions): static
    {
        $this->body['recaptchaError'] = ! empty($recaptchaExceptions);

        return $this;
    }
}
