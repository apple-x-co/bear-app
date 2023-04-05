<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use Aura\Session\Session as AuraSession;
use MyVendor\MyProject\Session\Session;
use Ray\Di\ProviderInterface;

class SessionProvider implements ProviderInterface
{
    public function __construct(
        private readonly AuraSession $session,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return new Session($this->session->getSegment(Session::class));
    }
}
