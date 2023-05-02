<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\AdminToken\AdminTokenRepositoryInterface;
use AppCore\Domain\EncrypterInterface;
use AppCore\Domain\SecureRandomInterface;
use AppCore\Infrastructure\Query\AdminTokenRemoveByAdminIdInterface;
use Aura\Auth\AuthFactory;
use Aura\Auth\Session\Segment;
use Aura\Auth\Session\Session;
use MyVendor\MyProject\Auth\AdminAuthenticator;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

use function ini_get;
use function sha1;

/**
 * Admin認証基盤を提供
 */
class AdminAuthenticatorProvider implements ProviderInterface
{
    /**
     * @param array<array-key, mixed> $cookie
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly AdminTokenRepositoryInterface $adminTokenRepository,
        private readonly AdminTokenRemoveByAdminIdInterface $adminTokenRemoveByAdminId,
        private readonly EncrypterInterface $encrypter,
        private readonly SecureRandomInterface $secureRandom,
        #[Named('cookie')] private readonly array $cookie,
        #[Named('pdo_dsn')] private readonly string $pdoDsn,
        #[Named('pdo_username')] private readonly string $pdoUsername,
        #[Named('pdo_password')] private readonly string $pdoPassword,
        #[Named('session_name')] private readonly string $sessionName,
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
        $authSegment = new Segment('BearApp\Admin');
        $authFactory = new AuthFactory($this->cookie, $authSession, $authSegment);

        $rememberCookieName = $this->sessionName . ':admin:remember_' . sha1(static::class);

        return new AdminAuthenticator(
            $this->adminRepository,
            $this->adminTokenRepository,
            $this->adminTokenRemoveByAdminId,
            $this->encrypter,
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
