<?php

declare(strict_types=1);

namespace AppCore\Presentation\Shared;

use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\Auth\AdminAuthenticatorInterface;
use AppCore\Domain\Auth\AdminContextInterface;
use AppCore\Domain\FlashMessenger\FlashMessageType;
use AppCore\Domain\FlashMessenger\FlashMessengerInterface;
use AppCore\Domain\Session\SessionInterface;
use Ray\Di\Di\Set;
use Ray\Di\ProviderInterface;

final readonly class AdminContext implements AdminContextInterface
{
    /**
     * @param ProviderInterface<AdminAuthenticatorInterface> $adminAuthenticatorProvider
     * @param ProviderInterface<SessionInterface>            $sessionProvider
     *
     * @SuppressWarnings("PHPMD.LongVariable")
     */
    public function __construct(
        #[Set(AdminAuthenticatorInterface::class)]
        private ProviderInterface $adminAuthenticatorProvider,
        private FlashMessengerInterface $flashMessenger,
        #[Set(SessionInterface::class)]
        private ProviderInterface $sessionProvider,
    ) {
    }

    private function authenticator(): AdminAuthenticatorInterface
    {
        return $this->adminAuthenticatorProvider->get();
    }

    private function session(): SessionInterface
    {
        return $this->sessionProvider->get();
    }

    /** @SuppressWarnings("PHPMD.StaticAccess") */
    public function isAllowed(string $resourceName, string $permission): bool
    {
        $requirePermission = Permission::from($permission);

        return $this->authenticator()->getAccessControl()->isAllowed($resourceName, $requirePermission);
    }

    public function displayName(): string
    {
        return $this->authenticator()->getIdentity()->displayName;
    }

    public function flashMessage(): string|null
    {
        // return $this->session()->getFlashMessage();
        return $this->flashMessenger->get(FlashMessageType::INFO);
    }

    public function setFlashMessage(string $message): void
    {
        // $this->session()->setFlashMessage($message);
        $this->flashMessenger->set(FlashMessageType::INFO, $message);
    }

    public function setSessionValue(string $key, string $val): void
    {
        $this->session()->set($key, $val);
    }

    public function getSessionValue(string $key, string|null $alt = null): mixed
    {
        return $this->session()->get($key, $alt);
    }

    public function resetSessionValue(string $key): void
    {
        $this->session()->reset($key);
    }
}
