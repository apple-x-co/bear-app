<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\AdminLogin;
use MyVendor\MyProject\Annotation\AdminLogout;
use MyVendor\MyProject\Annotation\AdminPasswordLock;
use MyVendor\MyProject\Annotation\AdminPasswordProtect;
use MyVendor\MyProject\Annotation\AdminVerifyPassword;
use MyVendor\MyProject\Annotation\UserGuard;
use MyVendor\MyProject\Annotation\UserLogin;
use MyVendor\MyProject\Annotation\UserLogout;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\UserAuthenticatorInterface;
use MyVendor\MyProject\Interceptor\AdminAuthenticate;
use MyVendor\MyProject\Interceptor\AdminAuthGuardian;
use MyVendor\MyProject\Interceptor\AdminPasswordProtector;
use MyVendor\MyProject\Interceptor\UserAuthenticate;
use MyVendor\MyProject\Interceptor\UserAuthGuardian;
use MyVendor\MyProject\Provider\AdminAuthenticatorProvider;
use MyVendor\MyProject\Provider\CookieProvider;
use MyVendor\MyProject\Provider\SessionProvider;
use MyVendor\MyProject\Provider\UserAuthenticatorProvider;
use MyVendor\MyProject\Resource\Page\AdminPage;
use MyVendor\MyProject\Resource\Page\UserPage;
use MyVendor\MyProject\Session\SessionInterface;
use Ray\Di\AbstractModule;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class SessionAuthModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(SessionInterface::class)
             ->toProvider(SessionProvider::class);

        $this->bind()
             ->annotatedWith('cookie')
             ->toProvider(CookieProvider::class);

        $this->admin();
        $this->user();
    }

    public function admin(): void
    {
        $this->bind(AdminAuthenticatorInterface::class)
             ->toProvider(AdminAuthenticatorProvider::class);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(AdminPage::class),
            $this->matcher->logicalOr(
                $this->matcher->logicalOr(
                    $this->matcher->annotatedWith(AdminLogin::class),
                    $this->matcher->annotatedWith(AdminLogout::class),
                ),
                $this->matcher->annotatedWith(AdminVerifyPassword::class),
            ),
            [AdminAuthenticate::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(AdminPage::class),
            $this->matcher->annotatedWith(AdminGuard::class),
            [AdminAuthGuardian::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(AdminPage::class),
            $this->matcher->logicalOr(
                $this->matcher->annotatedWith(AdminPasswordProtect::class),
                $this->matcher->annotatedWith(AdminPasswordLock::class)
            ),
            [AdminPasswordProtector::class],
        );
    }

    public function user(): void
    {
        $this->bind(UserAuthenticatorInterface::class)
             ->toProvider(UserAuthenticatorProvider::class);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(UserPage::class),
            $this->matcher->annotatedWith(UserLogin::class),
            [UserAuthenticate::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(UserPage::class),
            $this->matcher->annotatedWith(UserLogout::class),
            [UserAuthenticate::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(UserPage::class),
            $this->matcher->annotatedWith(UserGuard::class),
            [UserAuthGuardian::class],
        );
    }
}
