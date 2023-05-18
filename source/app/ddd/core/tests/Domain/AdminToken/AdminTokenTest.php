<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminToken;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AdminTokenTest extends TestCase
{
    public function testNotExpired(): void
    {
        $adminToken = new AdminToken(
            0,
            'example',
            (new DateTimeImmutable())->modify('+1 second'),
        );

        $this->assertFalse($adminToken->isExpired());
    }

    public function testExpired(): void
    {
        $adminToken = new AdminToken(
            0,
            'example',
            (new DateTimeImmutable())->modify('-1 second'),
        );

        $this->assertTrue($adminToken->isExpired());
    }
}
