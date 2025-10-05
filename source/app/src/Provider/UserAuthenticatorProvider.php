<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Attribute\Cookie;
use AppCore\Infrastructure\Shared\UserAuthenticator;
use Aura\Auth\AuthFactory;
use Aura\Auth\Session\Segment;
use Aura\Auth\Session\Session;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

use function ini_get;

/**
 * User認証基盤を提供
 *
 * @template-implements ProviderInterface<UserAuthenticator>
 */
class UserAuthenticatorProvider implements ProviderInterface
{
    private const string SEGMENT_NAME = 'Bebo\User';

    /** @param array<array-key, mixed> $cookie */
    public function __construct(
        #[Cookie]
        private readonly array $cookie,
        #[Named('pdo_dsn')]
        private readonly string $pdoDsn,
        #[Named('pdo_username')]
        private readonly string $pdoUsername,
        #[Named('pdo_password')]
        private readonly string $pdoPassword,
    ) {
    }

    /**
     * UserAuthenticator を生成する
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

        return new UserAuthenticator(
            $authFactory,
            (int) ini_get('session.gc_maxlifetime'),
            $this->pdoDsn,
            $this->pdoUsername,
            $this->pdoPassword,
            '/user/index',
            '/user/login',
        );
    }
}
