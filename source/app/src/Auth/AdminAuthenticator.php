<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\AdminToken\AdminToken;
use AppCore\Domain\AdminToken\AdminTokenRepositoryInterface;
use AppCore\Domain\EncrypterInterface;
use AppCore\Domain\SecureRandomInterface;
use AppCore\Domain\Throttle\Throttle;
use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Verifier\PasswordVerifier;
use DateInterval;
use DateTimeImmutable;
use MyVendor\MyProject\Query\AdminTokenRemoveByAdminIdInterface;
use MyVendor\MyProject\Query\ThrottleRemoveByKeyInterface;
use PDO;
use SensitiveParameter;
use Throwable;

use function explode;
use function setcookie;
use function time;

use const PASSWORD_BCRYPT;

/**
 * Admin認証基盤
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AdminAuthenticator implements AdminAuthenticatorInterface
{
    private const REMEMBER_SEPARATOR = ':';

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly AdminTokenRepositoryInterface $adminTokenRepository,
        private readonly AdminTokenRemoveByAdminIdInterface $adminTokenRemoveByAdminId,
        private readonly ThrottleRepositoryInterface $throttleRepository,
        private readonly ThrottleRemoveByKeyInterface $throttleRemoveByKey,
        private readonly EncrypterInterface $encrypter,
        private readonly SecureRandomInterface $secureRandom,
        private readonly string $rememberCookieName,
        private readonly AuthFactory $authFactory,
        private readonly int $authMaxAttempts,
        private readonly string $authAttemptInterval,
        private readonly int $sessionGcMaxlifetime,
        private readonly string $pdoDsn,
        private readonly string $pdoUsername,
        private readonly string $pdoPassword,
        private readonly string $authRedirect,
        private readonly string $unauthRedirect,
        private readonly string $passwordRedirect,
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
                'id', // as UserData[0]
            ],
            'admins',
            'admins.active = 1',
        );
    }

    public function login(string $username, #[SensitiveParameter] string $password, ?string $remoteIp = null): void
    {
        $throttle = $this->getAvailableThrottle($username, $remoteIp);

        $auth = $this->authFactory->newInstance();
        $loginService = $this->authFactory->newLoginService($this->getAdapter());
        try {
            $loginService->login(
                $auth,
                ['username' => $username, 'password' => $password],
            );
        } catch (Throwable $throwable) {
            $this->throttleRepository->store($throttle->countUp($remoteIp));

            throw $throwable;
        }

        $this->clearThrottles($throttle->throttleKey);

        $userData = $this->getUserData();
        $adminId = (int) $userData['id'];
        $this->clearRemember($adminId);
    }

    public function rememberLogin(
        string $username,
        #[SensitiveParameter] string $password,
        ?string $remoteIp = null,
    ): void {
        $throttle = $this->getAvailableThrottle($username, $remoteIp);

        $auth = $this->authFactory->newInstance();
        $loginService = $this->authFactory->newLoginService($this->getAdapter());
        try {
            $loginService->login(
                $auth,
                ['username' => $username, 'password' => $password],
            );
        } catch (Throwable $throwable) {
            $this->throttleRepository->store($throttle->countUp($remoteIp));

            throw $throwable;
        }

        $this->clearThrottles($throttle->throttleKey);

        $userData = $this->getUserData();
        $adminId = (int) $userData['id'];
        $this->setUpRemember($adminId);
    }

    public function getRememberCookieName(): string
    {
        return $this->rememberCookieName;
    }

    public function continueLogin(#[SensitiveParameter] string $payload): bool
    {
        [$encrypted, $token] = explode(self::REMEMBER_SEPARATOR, $payload);

        $adminToken = $this->adminTokenRepository->findByToken($token);
        if ($adminToken === null) {
            return false;
        }

        $decrypted = $this->encrypter->decrypt($encrypted);
        if (
            ($decrypted !== (string) $adminToken->adminId) ||
            $adminToken->isExpired()
        ) {
            $this->clearRemember($adminToken->adminId);

            return false;
        }

        $admin = $this->adminRepository->findById($adminToken->adminId);
        $auth = $this->authFactory->newInstance();
        $loginService = $this->authFactory->newLoginService($this->getAdapter());
        /** @psalm-suppress InvalidArgument */
        $loginService->forceLogin(
            $auth,
            $admin->username,
            ['id' => $admin->id],
        );

        $this->clearRemember($adminToken->adminId);
        $this->setUpRemember($adminToken->adminId);

        return true;
    }

    public function verifyPassword(string $username, string $password): void
    {
        $pdoAdapter = $this->getAdapter();
        $pdoAdapter->login(['username' => $username, 'password' => $password]);
    }

    public function logout(): void
    {
        $userData = $this->getUserData();
        $adminId = (int) $userData['id'];
        $this->clearRemember($adminId);

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

    public function getPasswordRedirect(): string
    {
        return $this->passwordRedirect;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getAvailableThrottle(string $username, ?string $remoteIp = null): Throttle
    {
        $throttle = $this->throttleRepository->findByThrottleKey($username);
        if ($throttle === null) {
            return new Throttle(
                $username,
                $remoteIp ?? '',
                0,
                $this->authMaxAttempts,
                $this->authAttemptInterval,
                (new DateTimeImmutable())->add(DateInterval::createFromDateString($this->authAttemptInterval)),
            );
        }

        if ($throttle->isExceeded()) {
            throw new MaxAttemptsExceeded();
        }

        return $throttle;
    }

    private function clearThrottles(string $throttleKey): void
    {
        ($this->throttleRemoveByKey)($throttleKey);
    }

    private function setUpRemember(int $adminId): void
    {
        $token = $this->secureRandom->hmac($this->secureRandom->randomBytes(32));
        $expireAt = (new DateTimeImmutable())->modify('+8 days');
        $adminToken = new AdminToken(
            $adminId,
            $token,
            $expireAt,
        );
        $this->adminTokenRepository->store($adminToken);

        $encrypted = $this->encrypter->encrypt((string) $adminId);

        setcookie(
            $this->rememberCookieName,
            $encrypted . self::REMEMBER_SEPARATOR . $token,
            [
                'expires' => $expireAt->getTimestamp(),
                'path' => '/',
                'httponly' => true,
            ],
        );
    }

    private function clearRemember(int $adminId): void
    {
        ($this->adminTokenRemoveByAdminId)($adminId);

        setcookie(
            $this->rememberCookieName,
            '',
            [
                'expires' => time() - 1,
                'path' => '/',
                'httponly' => true,
            ],
        );
    }
}
