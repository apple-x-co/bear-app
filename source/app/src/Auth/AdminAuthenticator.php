<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

use AppCore\Domain\AccessControl\Access;
use AppCore\Domain\AccessControl\AccessControl;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\AdminPermission\AdminPermission;
use AppCore\Domain\AdminPermission\AdminPermissionRepositoryInterface;
use AppCore\Domain\AdminToken\AdminToken;
use AppCore\Domain\AdminToken\AdminTokenRepositoryInterface;
use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Infrastructure\Query\AdminTokenRemoveByAdminIdInterface;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Verifier\PasswordVerifier;
use DateTimeImmutable;
use PDO;
use SensitiveParameter;

use function array_reduce;
use function assert;
use function explode;
use function is_int;
use function is_string;
use function setcookie;
use function time;

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
        private readonly AdminPermissionRepositoryInterface $adminPermissionRepository,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly AdminTokenRepositoryInterface $adminTokenRepository,
        private readonly AdminTokenRemoveByAdminIdInterface $adminTokenRemoveByAdminId,
        private readonly EncrypterInterface $encrypter,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly SecureRandomInterface $secureRandom,
        private readonly string $rememberCookieName,
        private readonly AuthFactory $authFactory,
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
            new PasswordVerifier($this->passwordHasher->hashType()),
            [
                'admins.username',
                'admins.password',
                'admins.id', // as UserData[0]
                'admins.display_name', // as UserData[1]
            ],
            'admins LEFT JOIN admin_deletes ON admins.id = admin_deletes.admin_id',
            'admins.active = 1 AND admin_deletes.admin_id IS NULL',
        );
    }

    public function login(string $username, #[SensitiveParameter] string $password): void
    {
        $auth = $this->authFactory->newInstance();
        $loginService = $this->authFactory->newLoginService($this->getAdapter());
        $loginService->login(
            $auth,
            ['username' => $username, 'password' => $password],
        );

        $adminId = $this->getUserId();
        if ($adminId === null) {
            return;
        }

        $this->clearRemember($adminId);
    }

    public function rememberLogin(string $username, #[SensitiveParameter] string $password): void
    {
        $auth = $this->authFactory->newInstance();
        $loginService = $this->authFactory->newLoginService($this->getAdapter());
        $loginService->login(
            $auth,
            ['username' => $username, 'password' => $password],
        );

        $adminId = $this->getUserId();
        if ($adminId === null) {
            return;
        }

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
            ['id' => $admin->id, 'display_name' => $admin->displayName],
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
        $adminId = $this->getUserId();
        if ($adminId !== null) {
            $this->clearRemember($adminId);
        }

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

    public function getDisplayName(): ?string
    {
        $userData = $this->getUserData();

        return $userData['display_name'] ?? null;
    }

    public function getUserId(): ?int
    {
        $userData = $this->getUserData();
        if (isset($userData['id'])) {
            return (int) $userData['id'];
        }

        return null;
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

    public function getIdentity(): AdminIdentity
    {
        $auth = $this->authFactory->newInstance();
        if (! $auth->isValid()) {
            throw new UnauthorizedException();
        }

        $userData = $auth->getUserData();
        assert(isset($userData['id']) && is_int($userData['id']));
        assert(isset($userData['display_name']) && is_string($userData['display_name']));

        return new AdminIdentity(
            $userData['id'],
            $userData['display_name'],
        );
    }

    public function getAccessControl(): AccessControl
    {
        $identity = $this->getIdentity();

        $adminPermissions = $this->adminPermissionRepository->findByAdminId($identity->id);
        if (empty($adminPermissions)) {
            return new AccessControl();
        }

        return array_reduce(
            $adminPermissions,
            static function (AccessControl $carry, AdminPermission $item) {
                return match ($item->access) {
                    Access::Allow => $carry
                        ->addResource($item->resourceName)
                        ->allow($item->resourceName, $item->permission),
                    Access::Deny => $carry
                        ->addResource($item->resourceName)
                        ->deny($item->resourceName, $item->permission)
                };
            },
            new AccessControl(),
        );
    }
}
