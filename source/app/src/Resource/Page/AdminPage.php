<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

class AdminPage extends ResourceObject
{
    protected RouterInterface $router;

    #[Inject, Named('g_recaptcha_site_key')]
    public function setGRecaptchaSiteKey(string $gRecaptchaSiteKey): void
    {
        $this->body['gRecaptchaSiteKey'] = $gRecaptchaSiteKey;
    }

    #[Inject]
    public function setRouter(RouterInterface $router): void
    {
        $this->body['router'] = $router;
    }
}
