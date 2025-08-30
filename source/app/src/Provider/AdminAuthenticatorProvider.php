<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\AdminPermission\AdminPermissionRepositoryInterface;
use AppCore\Domain\AdminToken\AdminTokenRepositoryInterface;
use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Infrastructure\Query\AdminTokenRemoveByAdminIdInterface;
use AppCore\Infrastructure\Shared\AdminAuthenticator;
use Aura\Auth\AuthFactory;
use Aura\Auth\Session\Segment;
use Aura\Auth\Session\Session;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

use function ini_get;
use function sha1;

/**
 * Admin認証基盤を提供
 *
 * @template-implements ProviderInterface<AdminAuthenticator>
 */
readonly class AdminAuthenticatorProvider implements ProviderInterface
{
    private const string SEGMENT_NAME = 'Bebo\Admin';

    /**
     * @param array<array-key, mixed> $cookie
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private AdminPermissionRepositoryInterface $adminPermissionRepository,
        private AdminRepositoryInterface $adminRepository,
        private AdminTokenRepositoryInterface $adminTokenRepository,
        private AdminTokenRemoveByAdminIdInterface $adminTokenRemoveByAdminId,
        private EncrypterInterface $encrypter,
        private PasswordHasherInterface $passwordHasher,
        private SecureRandomInterface $secureRandom,
        #[Named('cookie')]
        private array $cookie,
        #[Named('pdo_dsn')]
        private string $pdoDsn,
        #[Named('pdo_username')]
        private string $pdoUsername,
        #[Named('pdo_password')]
        private string $pdoPassword,
        #[Named('session_name')]
        private string $sessionName,
    ) {
    }

    /**
     * AdminAuthenticator を生成する
     *
     * Provider の中で loginService や logoutService などを生成すると
     * PdoAdapter が動いてしまいDB接続がない環境（たとえば GitHub Actions）で PDOException が発生する
     * そのため各 service は Authenticator の中で生成する。
     *
     * {@inheritDoc}
     */
    public function get()
    {
        $authSession = new Session($this->cookie);
        $authSegment = new Segment(self::SEGMENT_NAME);
        $authFactory = new AuthFactory($this->cookie, $authSession, $authSegment);

        $rememberCookieName = $this->sessionName . ':admin:remember_' . sha1(static::class);

        return new AdminAuthenticator(
            $this->adminPermissionRepository,
            $this->adminRepository,
            $this->adminTokenRepository,
            $this->adminTokenRemoveByAdminId,
            $this->encrypter,
            $this->passwordHasher,
            $this->secureRandom,
            $rememberCookieName,
            $authFactory,
            (int) ini_get('session.gc_maxlifetime'),
            $this->pdoDsn,
            $this->pdoUsername,
            $this->pdoPassword,
            '/admin/index',
            '/admin/login',
            '/admin/password-confirm',
        );
    }
}
