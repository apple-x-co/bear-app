<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Session\SessionInterface;
use Ray\Di\Di\Inject;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
class AdminPage extends ResourceObject
{
    protected SessionInterface $session;

    #[Inject]
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }
}
