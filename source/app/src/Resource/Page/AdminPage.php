<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use AppCore\Domain\Auth\AdminContextInterface;
use BEAR\Resource\ResourceObject;
use Ray\Di\Di\Inject;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
class AdminPage extends ResourceObject
{
    protected AdminContextInterface $context;

    #[Inject]
    public function setAdminContext(AdminContextInterface $context): void
    {
        $this->context = $context;

        $this->body['context'] = $context;
    }
}
