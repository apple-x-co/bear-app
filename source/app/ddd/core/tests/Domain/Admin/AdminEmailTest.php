<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AdminEmailTest extends TestCase
{
    public function testVerified(): void
    {
        $adminEmail = (new AdminEmail(0, 'test@example.com'))->verified();

        $this->assertInstanceOf(DateTimeImmutable::class, $adminEmail->verifiedAt);
    }
}
