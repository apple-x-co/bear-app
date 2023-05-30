<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Infrastructure\Query\AdminDeleteCommandInterface;
use DateTimeImmutable;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\AdminLogout;
use MyVendor\MyProject\Annotation\AdminPasswordProtect;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Input\Admin\DeleteInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;
use Throwable;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class Delete extends AdminPage
{
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        private readonly AdminDeleteCommandInterface $adminDeleteCommand,
        private readonly AdminRepositoryInterface $adminRepository,
        #[Named('admin_delete_form')] protected readonly FormInterface $form,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('SMTP')] private readonly TransportInterface $transport,
    ) {
        $this->body['form'] = $this->form;
    }

    #[AdminGuard]
    #[AdminPasswordProtect]
    public function onGet(): static
    {
        return $this;
    }

    /**
     * @FormValidation()
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    #[AdminGuard]
    #[Transactional]
    #[AdminLogout]
    public function onPost(DeleteInput $delete): static
    {
        $adminId = (int) $this->adminAuthenticator->getUserId();

        $this->adminDeleteCommand->add(
            $adminId,
            new DateTimeImmutable(),
            (new DateTimeImmutable())->modify('+1 day'),
        );

        $admin = $this->adminRepository->findById($adminId);
        foreach ($admin->emails as $adminEmail) {
            try {
                $this->transport->send(
                    (new Email())
                        ->setFrom($this->adminAddress)
                        ->setTo([new Address($adminEmail->emailAddress, $admin->username)])
                        ->setTemplateId('admin_deleted')
                        ->setTemplateVars(['displayName' => $admin->displayName])
                );
            } catch (Throwable $throwable) {
                $this->logger->log((string) $throwable);
            }
        }

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
