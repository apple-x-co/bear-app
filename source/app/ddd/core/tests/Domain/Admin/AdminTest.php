<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    public function testMarkEmailAsVerified(): void
    {
        $admin = (new Admin(
            'test',
            'password',
            'Example',
            true,
            [new AdminEmail(0, 'test@example.com')],
        ))->markEmailAsVerified('test@example.com');

        $this->assertInstanceOf(DateTimeImmutable::class, $admin->emails[0]->verifiedAt);
    }

    public function testAddEmail(): void
    {
        $admin = (new Admin(
            'test',
            'password',
            'Example',
            true,
            [],
        ))->addEmail(new AdminEmail(0, 'test@example.com'));

        $this->assertCount(1, $admin->emails);
    }

    public function testRemoveEmail(): void
    {
        $adminEmail = new AdminEmail(0, 'test@example.com', null, null, null, 1);
        $admin = (new Admin(
            'test',
            'password',
            'Example',
            true,
            [$adminEmail],
        ))->removeEmail($adminEmail);

        $this->assertTrue($admin->emails[0]->isRemoval());
    }
}
