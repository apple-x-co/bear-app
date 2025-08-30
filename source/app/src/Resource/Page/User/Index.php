<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\User;

use AppCore\Domain\Auth\UserAuthenticatorInterface;
use MyVendor\MyProject\Annotation\UserGuard;
use MyVendor\MyProject\Resource\Page\UserPage;

class Index extends UserPage
{
    public function __construct(
        private readonly UserAuthenticatorInterface $authenticator,
    ) {
    }

    #[UserGuard]
    public function onGet(): static
    {
        $this->body['username'] = $this->authenticator->getUserName();

        return $this;
    }
}
