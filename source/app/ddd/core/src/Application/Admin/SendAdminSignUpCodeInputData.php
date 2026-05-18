<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

readonly class SendAdminSignUpCodeInputData
{
    public function __construct(public string $emailAddress)
    {
    }
}
