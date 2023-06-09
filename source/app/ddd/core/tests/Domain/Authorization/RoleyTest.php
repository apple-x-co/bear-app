<?php

declare(strict_types=1);

namespace AppCore\Domain\Authorization;

use PHPUnit\Framework\TestCase;

class RoleyTest extends TestCase
{
    public function testSuperUser(): void
    {
        $role = new Role([
            new Policy('dummy', [], [Permission::All]),
        ]);

        $this->assertTrue($role->allowed(Permission::Account));
    }

    public function testUser(): void
    {
        $role = new Role([
            new Policy('dummy', [], [Permission::Account]),
        ]);

        $this->assertTrue($role->allowed(Permission::Account));
    }

    public function testAllowedObjectId(): void
    {
        $role = new Role([
            new Policy('members', [1], [Permission::MemberList, Permission::MemberView]),
        ]);

        $this->assertEquals([1], $role->allowedObjectIds(Permission::MemberList));
    }
}
