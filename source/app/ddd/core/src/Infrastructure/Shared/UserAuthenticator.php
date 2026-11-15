<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Auth\UnauthorizedException;
use AppCore\Domain\Auth\UserAuthenticatorInterface;
use AppCore\Domain\Auth\UserIdentity;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Remember\RememberService;
use Aura\Auth\Status;
use Aura\Auth\Verifier\PasswordVerifier;
use Override;
use PDO;
use SensitiveParameter;

use function assert;
use function is_array;
use function is_int;
use function is_string;

use const PASSWORD_BCRYPT;

/** User認証基盤 */
final readonly class UserAuthenticator implements UserAuthenticatorInterface
{
    private const string FROM = 'users';
    private const string WHERE = 'users.active = 1';
    private const string REMEMBER_TABLE = 'user_remembers';
    private const int REMEMBER_TTL = 691200; // 8 日
    private const int SESSION_EXPIRE_TTL = 86400; // Aura.Auth の ResumeService の既定値

    public function __construct(
        private string $rememberCookieName,
        private bool $rememberCookieSecure,
        private AuthFactory $authFactory,
        private int $sessionGcMaxlifetime,
        private string $pdoDsn,
        private string $pdoUsername,
        private string $pdoPassword,
        private string $authRedirect,
        private string $unauthRedirect,
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
            new PasswordVerifier(PASSWORD_BCRYPT),
            [
                'users.username',
                'users.password',
                'users.id', // as UserData[0]
                'users.display_name', // as UserData[1]
            ],
            self::FROM,
            self::WHERE,
        );
    }

    /**
     * Remember me（Aura.Auth の RememberService）
     *
     * Cookie から復帰するたびに、ログインと同じ条件（有効）でユーザーを読み直す。
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
                        'SELECT users.id, users.display_name FROM ' . self::FROM .
                        ' WHERE users.username = :username AND ' . self::WHERE,
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
        $pdo = $this->getPdo();
        $auth = $this->authFactory->newInstance();
        $rememberService = $this->getRememberService($pdo);
        $loginService = $this->authFactory->newLoginService($this->getAdapter($pdo), $rememberService);
        $loginService->login($auth, ['username' => $username, 'password' => $password]);
        $rememberService->forget($auth);
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
        $pdo = $this->getPdo();
        $auth = $this->authFactory->newInstance();
        $rememberService = $this->getRememberService($pdo);
        $loginService = $this->authFactory->newLoginService($this->getAdapter($pdo), $rememberService);
        $loginService->login($auth, ['username' => $username, 'password' => $password]);
        $rememberService->remember($auth);
    }

    #[Override]
    public function logout(): void
    {
        $pdo = $this->getPdo();
        $auth = $this->authFactory->newInstance();
        $logoutService = $this->authFactory->newLogoutService($this->getAdapter($pdo), $this->getRememberService($pdo));
        $logoutService->forceLogout($auth);
    }

    /**
     * セッションを再開する。未ログインなら Remember me の Cookie からの復帰も試す
     *
     * Aura.Auth はセッションがタイムアウトすると状態を IDLE / EXPIRED にし、ANON のときしか Cookie から復帰しない。
     * タイムアウト後も復帰できるよう、いったん ANON に戻して復帰を試し、できなければ元の状態に戻す
     */
    private function resume(): Auth
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

    /** {@inheritdoc} */
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
    public function getIdentity(): UserIdentity
    {
        $auth = $this->authFactory->newInstance();
        if (! $auth->isValid() && ! $auth->isRemembered()) {
            throw new UnauthorizedException();
        }

        $userData = $auth->getUserData();
        assert(isset($userData['id']) && is_int($userData['id']) && $userData['id'] > 0);
        assert(isset($userData['display_name']) && is_string($userData['display_name']));

        return new UserIdentity(
            $userData['id'],
            $userData['display_name'],
        );
    }
}
