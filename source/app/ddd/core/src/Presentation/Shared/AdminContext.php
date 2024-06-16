<?php

declare(strict_types=1);

namespace AppCore\Presentation\Shared;

use AppCore\Domain\AccessControl\Permission;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Session\SessionInterface;
use Ray\Di\Di\Set;
use Ray\Di\ProviderInterface;

final class AdminContext implements AdminContextInterface
{
    public function __construct(
        #[Set(AdminAuthenticatorInterface::class)]
        private readonly ProviderInterface $adminAuthenticatorProvider,
        #[Set(SessionInterface::class)]
        private readonly ProviderInterface $sessionProvider,
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
        return $this->session()->getFlashMessage();
    }

    public function setFlashMessage(string $message): void
    {
        $this->session()->setFlashMessage($message);
    }

    public function setSessionValue(string $key, string $val): void
    {
        $this->session()->set($key, $val);
    }

    public function getSessionValue(string $key, ?string $alt = null): mixed
    {
        $this->session()->get($key, $alt);
    }

    public function resetSessionValue(string $key): void
    {
        $this->session()->reset($key);
    }
}
