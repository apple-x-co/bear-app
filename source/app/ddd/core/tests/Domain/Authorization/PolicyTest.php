<?php

declare(strict_types=1);

namespace AppCore\Domain\Authorization;

use PHPUnit\Framework\TestCase;

class PolicyTest extends TestCase
{
    public function testHasPermissionTrue(): void
    {
        $policy = new Policy('dummy', [], [Permission::All]);

        $this->assertTrue($policy->hasPermission(Permission::All));
    }

    public function testHasPermissionFalse(): void
    {
        $policy = new Policy('dummy', [], [Permission::Account]);

        $this->assertFalse($policy->hasPermission(Permission::All));
    }

    public function testHasPermissionAll(): void
    {
        $policy = new Policy('dummy', [], [Permission::All]);

        $this->assertTrue($policy->hasPermission(Permission::Account));
    }
}
