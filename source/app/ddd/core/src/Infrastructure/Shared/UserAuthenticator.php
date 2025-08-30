<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Auth\UnauthorizedException;
use AppCore\Domain\Auth\UserAuthenticatorInterface;
use AppCore\Domain\Auth\UserIdentity;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Verifier\PasswordVerifier;
use PDO;
use SensitiveParameter;

use function assert;
use function is_int;
use function is_string;

use const PASSWORD_BCRYPT;

/** User認証基盤 */
readonly class UserAuthenticator implements UserAuthenticatorInterface
{
    public function __construct(
        private AuthFactory $authFactory,
        private int $sessionGcMaxlifetime,
        private string $pdoDsn,
        private string $pdoUsername,
        private string $pdoPassword,
        private string $authRedirect,
        private string $unauthRedirect,
    ) {
    }

    private function getAdapter(): PdoAdapter
    {
        return $this->authFactory->newPdoAdapter(
            new PDO($this->pdoDsn, $this->pdoUsername, $this->pdoPassword),
            new PasswordVerifier(PASSWORD_BCRYPT),
            [
                'users.username',
                'users.password',
                'users.id', // as UserData[0]
                'users.display_name', // as UserData[1]
            ],
            'users',
            'users.active = 1',
        );
    }

    public function login(
        string $username,
        #[SensitiveParameter]
        string $password,
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

    public function getUserName(): string|null
    {
        return $this->authFactory->newInstance()->getUserName();
    }

    /** {@inheritdoc} */
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

    public function getIdentity(): UserIdentity
    {
        $auth = $this->authFactory->newInstance();
        if (! $auth->isValid()) {
            throw new UnauthorizedException();
        }

        $userData = $auth->getUserData();
        assert(isset($userData['id']) && is_int($userData['id']));
        assert(isset($userData['display_name']) && is_string($userData['display_name']));

        return new UserIdentity(
            $userData['id'],
            $userData['display_name'],
        );
    }
}
