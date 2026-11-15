<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\AccessControl\Access;
use AppCore\Domain\AccessControl\AccessControl;
use AppCore\Domain\AdminPermission\AdminPermission;
use AppCore\Domain\AdminPermission\AdminPermissionRepositoryInterface;
use AppCore\Domain\Auth\AdminAuthenticatorInterface;
use AppCore\Domain\Auth\AdminIdentity;
use AppCore\Domain\Auth\AuthStorageException;
use AppCore\Domain\Auth\UnauthorizedException;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Remember\RememberService;
use Aura\Auth\Status;
use Aura\Auth\Verifier\PasswordVerifier;
use Override;
use PDO;
use PDOException;
use SensitiveParameter;

use function array_reduce;
use function assert;
use function is_array;
use function is_int;
use function is_string;

/**
 * Admin認証基盤
 *
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
final class AdminAuthenticator implements AdminAuthenticatorInterface
{
    private const string FROM = 'admins LEFT JOIN admin_deletes ON admins.id = admin_deletes.admin_id';
    private const string WHERE = 'admins.active = 1 AND admin_deletes.admin_id IS NULL';
    private const string REMEMBER_TABLE = 'admin_remembers';
    private const int REMEMBER_TTL = 691200; // 8 日
    private const int SESSION_EXPIRE_TTL = 86400; // Aura.Auth の ResumeService の既定値

    /**
     * @SuppressWarnings("PHPMD.ExcessiveParameterList")
     * @SuppressWarnings("PHPMD.LongVariable")
     */
    public function __construct(
        private readonly AdminPermissionRepositoryInterface $adminPermissionRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly string $rememberCookieName,
        private readonly bool $rememberCookieSecure,
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

    private function getPdo(): PDO
    {
        return new PDO($this->pdoDsn, $this->pdoUsername, $this->pdoPassword);
    }

    private function getAdapter(PDO $pdo): PdoAdapter
    {
        return $this->authFactory->newPdoAdapter(
            $pdo,
            new PasswordVerifier($this->passwordHasher->hashType()),
            [
                'admins.username',
                'admins.password',
                'admins.id', // as UserData[0]
                'admins.display_name', // as UserData[1]
            ],
            self::FROM,
            self::WHERE,
        );
    }

    /**
     * Remember me（Aura.Auth の RememberService）
     *
     * Cookie から復帰するたびに、ログインと同じ条件（有効・削除申請中でない）で管理者を読み直す。
     * 条件を満たさなければ null を返し、トークンを破棄させる
     *
     * user_loader は Aura.Auth に渡すコールバックで、PdoAdapter と同じ FROM・WHERE を使う必要があるため、
     * CQRS（Ray.MediaQuery と SQL ファイル）ではなく、ここで直接 SQL を組み立てる
     */
    private function getRememberService(PDO $pdo): RememberService
    {
        return $this->authFactory->newRememberService(
            $this->authFactory->newPdoRememberStorage($pdo, self::REMEMBER_TABLE),
            [
                'name' => $this->rememberCookieName,
                'ttl' => self::REMEMBER_TTL,
                'secure' => $this->rememberCookieSecure,
                'httponly' => true,
                'samesite' => 'Strict',
                'path' => '/',
                'user_loader' => static function (string $username) use ($pdo): array|null {
                    $statement = $pdo->prepare(
                        'SELECT admins.id, admins.display_name FROM ' . self::FROM .
                        ' WHERE admins.username = :username AND ' . self::WHERE,
                    );
                    $statement->execute(['username' => $username]);
                    $row = $statement->fetch(PDO::FETCH_ASSOC);

                    return is_array($row) ? $row : null;
                },
            ],
        );
    }

    /** 記憶しないログインでは、以前のログインで残った Remember me の Cookie とトークンを消す */
    #[Override]
    public function login(
        string $username,
        #[SensitiveParameter]
        string $password,
    ): void {
        try {
            $pdo = $this->getPdo();
            $auth = $this->authFactory->newInstance();
            $rememberService = $this->getRememberService($pdo);
            $loginService = $this->authFactory->newLoginService($this->getAdapter($pdo), $rememberService);
            $loginService->login($auth, ['username' => $username, 'password' => $password]);
            $rememberService->forget($auth);
        } catch (PDOException $e) {
            throw new AuthStorageException('Failed to access the authentication storage', 0, $e);
        }
    }

    /**
     * LoginService に `remember` を渡すと、PdoAdapter が `remember` まで SQL にバインドして失敗する（aura/auth 7.0.0）。
     * そのためログインのあとで RememberService::remember() を直接呼ぶ
     */
    #[Override]
    public function rememberLogin(
        string $username,
        #[SensitiveParameter]
        string $password,
    ): void {
        try {
            $pdo = $this->getPdo();
            $auth = $this->authFactory->newInstance();
            $rememberService = $this->getRememberService($pdo);
            $loginService = $this->authFactory->newLoginService($this->getAdapter($pdo), $rememberService);
            $loginService->login($auth, ['username' => $username, 'password' => $password]);
            $rememberService->remember($auth);
        } catch (PDOException $e) {
            throw new AuthStorageException('Failed to access the authentication storage', 0, $e);
        }
    }

    #[Override]
    public function verifyPassword(string $username, string $password): void
    {
        try {
            $pdoAdapter = $this->getAdapter($this->getPdo());
            $pdoAdapter->login(['username' => $username, 'password' => $password]);
        } catch (PDOException $e) {
            throw new AuthStorageException('Failed to access the authentication storage', 0, $e);
        }
    }

    /**
     * Aura.Auth の LogoutService は、Remember me のトークンを消してからセッションを消す。
     * DB の障害でトークンを消せなくてもログアウトできるよう、先にセッションを消してからトークンと Cookie を消す
     */
    #[Override]
    public function logout(): void
    {
        $auth = $this->authFactory->newInstance();
        $this->authFactory->newLogoutService()->forceLogout($auth);

        try {
            $this->getRememberService($this->getPdo())->forget($auth);
        } catch (PDOException $e) {
            throw new AuthStorageException('Failed to access the authentication storage', 0, $e);
        }
    }

    /**
     * セッションを再開する。未ログインなら Remember me の Cookie からの復帰も試す
     *
     * Aura.Auth はセッションがタイムアウトすると状態を IDLE / EXPIRED にし、ANON のときしか Cookie から復帰しない。
     * タイムアウト後も復帰できるよう、いったん ANON に戻して復帰を試し、できなければ元の状態に戻す
     */
    private function resume(): Auth
    {
        try {
            return $this->resumeWithRemember();
        } catch (PDOException $e) {
            throw new AuthStorageException('Failed to access the authentication storage', 0, $e);
        }
    }

    private function resumeWithRemember(): Auth
    {
        $pdo = $this->getPdo();
        $auth = $this->authFactory->newInstance();
        $rememberService = $this->getRememberService($pdo);
        $resumeService = $this->authFactory->newResumeService(
            $this->getAdapter($pdo),
            $this->sessionGcMaxlifetime,
            self::SESSION_EXPIRE_TTL,
            $rememberService,
        );
        $resumeService->resume($auth);

        if ($auth->isIdle() || $auth->isExpired()) {
            $timeoutStatus = $auth->getStatus();
            $auth->setStatus(Status::ANON);
            if (! $rememberService->resume($auth)) {
                $auth->setStatus($timeoutStatus);
            }
        }

        return $auth;
    }

    #[Override]
    public function isValid(): bool
    {
        $auth = $this->resume();

        return $auth->isValid() || $auth->isRemembered();
    }

    #[Override]
    public function isRemembered(): bool
    {
        return $this->resume()->isRemembered();
    }

    #[Override]
    public function isExpired(): bool
    {
        return $this->resume()->isExpired();
    }

    #[Override]
    public function getUserName(): string|null
    {
        return $this->authFactory->newInstance()->getUserName();
    }

    #[Override]
    public function getDisplayName(): string|null
    {
        $userData = $this->getUserData();

        return $userData['display_name'] ?? null;
    }

    /** @return positive-int|null */
    #[Override]
    public function getUserId(): int|null
    {
        $userData = $this->getUserData();
        if (isset($userData['id'])) {
            $userId = (int) $userData['id'];
            assert($userId > 0);

            return $userId;
        }

        return null;
    }

    /** {@inheritdoc}*/
    #[Override]
    public function getUserData(): array
    {
        return $this->authFactory->newInstance()->getUserData();
    }

    #[Override]
    public function getAuthRedirect(): string
    {
        return $this->authRedirect;
    }

    #[Override]
    public function getUnauthRedirect(): string
    {
        return $this->unauthRedirect;
    }

    #[Override]
    public function getPasswordRedirect(): string
    {
        return $this->passwordRedirect;
    }

    #[Override]
    public function getIdentity(): AdminIdentity
    {
        $auth = $this->authFactory->newInstance();
        if (! $auth->isValid() && ! $auth->isRemembered()) {
            throw new UnauthorizedException();
        }

        $userData = $auth->getUserData();
        assert(isset($userData['id']) && is_int($userData['id']) && $userData['id'] > 0);
        assert(isset($userData['display_name']) && is_string($userData['display_name']));

        return new AdminIdentity(
            $userData['id'],
            $userData['display_name'],
        );
    }

    #[Override]
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
