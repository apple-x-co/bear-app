<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Attribute\Cookie;
use AppCore\Attribute\SessionName;
use AppCore\Domain\Auth\AdminAuthenticatorInterface;
use AppCore\Domain\Auth\UserAuthenticatorInterface;
use AppCore\Domain\FlashMessenger\FlashMessengerInterface;
use AppCore\Domain\Session\SessionInterface;
use AppCore\Infrastructure\Shared\FlashMessenger;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\AdminLogin;
use MyVendor\MyProject\Annotation\AdminLogout;
use MyVendor\MyProject\Annotation\AdminPasswordLock;
use MyVendor\MyProject\Annotation\AdminPasswordProtect;
use MyVendor\MyProject\Annotation\AdminVerifyPassword;
use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\Annotation\UserGuard;
use MyVendor\MyProject\Annotation\UserLogin;
use MyVendor\MyProject\Annotation\UserLogout;
use MyVendor\MyProject\Interceptor\AdminAuthentication;
use MyVendor\MyProject\Interceptor\AdminAuthGuardian;
use MyVendor\MyProject\Interceptor\AdminAuthorization;
use MyVendor\MyProject\Interceptor\AdminKeepAuthenticated;
use MyVendor\MyProject\Interceptor\AdminPasswordProtector;
use MyVendor\MyProject\Interceptor\UserAuthentication;
use MyVendor\MyProject\Interceptor\UserAuthGuardian;
use MyVendor\MyProject\Provider\AdminAuthenticatorProvider;
use MyVendor\MyProject\Provider\CookieProvider;
use MyVendor\MyProject\Provider\SessionProvider;
use MyVendor\MyProject\Provider\UserAuthenticatorProvider;
use MyVendor\MyProject\Resource\Page\BaseAdminPage;
use MyVendor\MyProject\Resource\Page\BaseUserPage;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

/** @SuppressWarnings("PHPMD.CouplingBetweenObjects") */
class SessionAuthModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(SessionInterface::class)->toProvider(SessionProvider::class)->in(Scope::SINGLETON);

        $this->bind()->annotatedWith(Cookie::class)->toProvider(CookieProvider::class);

        $this->bind()->annotatedWith(SessionName::class)->toInstance('bear-app');

        $this->bind(FlashMessengerInterface::class)->to(FlashMessenger::class)->in(Scope::SINGLETON);

        $this->admin();
        $this->user();
    }

    private function admin(): void
    {
        $this->bind()->annotatedWith('admin_auth_max_attempts')->toInstance(10);
        $this->bind()->annotatedWith('admin_auth_attempt_interval')->toInstance('30 minutes');

        $this->bind(AdminAuthenticatorInterface::class)
             ->toProvider(AdminAuthenticatorProvider::class)
             ->in(Scope::SINGLETON);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseAdminPage::class),
            $this->matcher->logicalAnd(
                $this->matcher->startsWith('on'),
                $this->matcher->logicalOr(
                    $this->matcher->logicalOr(
                        $this->matcher->annotatedWith(AdminLogin::class),
                        $this->matcher->annotatedWith(AdminLogout::class),
                    ),
                    $this->matcher->annotatedWith(AdminVerifyPassword::class),
                ),
            ),
            [AdminAuthentication::class],
        );

        $this->bind(AdminKeepAuthenticated::class); // 複数の Interceptor を渡すと Untargeted が発生するので事前に束縛をする
        $this->bind(AdminAuthGuardian::class); // 複数の Interceptor を渡すと Untargeted が発生するので事前に束縛をする
        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseAdminPage::class),
            $this->matcher->annotatedWith(AdminGuard::class),
            [AdminKeepAuthenticated::class, AdminAuthGuardian::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseAdminPage::class),
            $this->matcher->logicalOr(
                $this->matcher->annotatedWith(AdminPasswordProtect::class),
                $this->matcher->annotatedWith(AdminPasswordLock::class),
            ),
            [AdminPasswordProtector::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseAdminPage::class),
            $this->matcher->annotatedWith(RequiredPermission::class),
            [AdminAuthorization::class],
        );
    }

    private function user(): void
    {
        $this->bind(UserAuthenticatorInterface::class)
             ->toProvider(UserAuthenticatorProvider::class)
             ->in(Scope::SINGLETON);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseUserPage::class),
            $this->matcher->annotatedWith(UserLogin::class),
            [UserAuthentication::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseUserPage::class),
            $this->matcher->annotatedWith(UserLogout::class),
            [UserAuthentication::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseUserPage::class),
            $this->matcher->annotatedWith(UserGuard::class),
            [UserAuthGuardian::class],
        );
    }
}
