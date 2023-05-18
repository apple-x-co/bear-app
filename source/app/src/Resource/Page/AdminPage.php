<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Session\SessionInterface;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
class AdminPage extends ResourceObject
{
    protected RouterInterface $router;
    protected SessionInterface $session;

    /** @SuppressWarnings(PHPMD.LongVariable) */
    #[Inject, Named('google_recaptcha_site_key')]
    public function setGoogleRecaptchaSiteKey(string $googleRecaptchaSiteKey): void
    {
        $this->body['googleRecaptchaSiteKey'] = $googleRecaptchaSiteKey;
    }

    #[Inject]
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
        $this->body['router'] = $router;
    }

    #[Inject]
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
        $this->body['session'] = $session;
    }

    #[Inject]
    public function setAuthenticator(AdminAuthenticatorInterface $authenticator): void
    {
        $this->body['authenticator'] = $authenticator;
    }
}
