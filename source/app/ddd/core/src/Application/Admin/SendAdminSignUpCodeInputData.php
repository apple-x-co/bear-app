<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

final readonly class SendAdminSignUpCodeInputData
{
    public function __construct(public string $emailAddress)
    {
    }
}
