<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Verifier\PasswordVerifier;
use PDO;
use SensitiveParameter;

use const PASSWORD_BCRYPT;

/**
 * User認証基盤
 */
class UserAuthenticator implements UserAuthenticatorInterface
{
    public function __construct(
        private readonly AuthFactory $authFactory,
        private readonly int $sessionGcMaxlifetime,
        private readonly string $pdoDsn,
        private readonly string $pdoUsername,
        private readonly string $pdoPassword,
        private readonly string $authRedirect,
        private readonly string $unauthRedirect,
    ) {
    }

    private function getAdapter(): PdoAdapter
    {
        return $this->authFactory->newPdoAdapter(
            new PDO($this->pdoDsn, $this->pdoUsername, $this->pdoPassword),
            new PasswordVerifier(PASSWORD_BCRYPT),
            [
                'username',
                'password',
                'id', // as UserData
            ],
            'users',
            'users.active = 1',
        );
    }

    public function login(
        string $username,
        #[SensitiveParameter] string $password
    ): void {
        $auth = $this->authFactory->newInstance();
        $loginService = $this->authFactory->newLoginService($this->getAdapter());
        $loginService->login(
            $auth,
            ['username' => $username, 'password' => $password],
        );
    }

    public function logout(): void
    {
        $auth = $this->authFactory->newInstance();
        $logoutService = $this->authFactory->newLogoutService($this->getAdapter());
        $logoutService->forceLogout($auth);
    }

    private function resume(): Auth
    {
        $auth = $this->authFactory->newInstance();
        $resumeService = $this->authFactory->newResumeService($this->getAdapter(), $this->sessionGcMaxlifetime);
        $resumeService->resume($auth);

        return $auth;
    }

    public function isValid(): bool
    {
        return $this->resume()->isValid();
    }

    public function isExpired(): bool
    {
        return $this->resume()->isExpired();
    }

    public function getUserName(): ?string
    {
        return $this->authFactory->newInstance()->getUserName();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserData(): array
    {
        return $this->authFactory->newInstance()->getUserData();
    }

    public function getAuthRedirect(): string
    {
        return $this->authRedirect;
    }

    public function getUnauthRedirect(): string
    {
        return $this->unauthRedirect;
    }
}
