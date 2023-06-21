<?php

declare(strict_types=1);

namespace AppCore\Domain\AccessControl;

use PHPUnit\Framework\TestCase;

class AccessControlTest extends TestCase
{
    public function testAllowedNone(): void
    {
        $alc = (new AccessControl())
            ->addResource('Blog');

        $this->assertFalse($alc->isAllowed('Blog', Permission::Read));
        $this->assertFalse($alc->isAllowed('Blog', Permission::Write));
    }

    public function testAllowedReadOnly(): void
    {
        $alc = (new AccessControl())
            ->addResource('Blog')
            ->allow('Blog', Permission::Read);

        $this->assertTrue($alc->isAllowed('Blog', Permission::Read));
        $this->assertFalse($alc->isAllowed('Blog', Permission::Write));
    }

    public function testAllowedReadWrite(): void
    {
        $alc = (new AccessControl())
            ->addResource('Blog')
            ->allow('Blog', Permission::Read)
            ->allow('Blog', Permission::Write);

        $this->assertTrue($alc->isAllowed('Blog', Permission::Read));
        $this->assertTrue($alc->isAllowed('Blog', Permission::Write));
    }

    public function testAllowedPrivilege(): void
    {
        $alc = (new AccessControl())
            ->addResource('Blog')
            ->allow('Blog', Permission::Privilege);

        $this->assertTrue($alc->isAllowed('Blog', Permission::Read));
        $this->assertTrue($alc->isAllowed('Blog', Permission::Write));
    }

    public function testDeniedWrite(): void
    {
        $alc = (new AccessControl())
            ->addResource('Blog')
            ->deny('Blog', Permission::Write);

        $this->assertTrue($alc->isAllowed('Blog', Permission::Read));
        $this->assertFalse($alc->isAllowed('Blog', Permission::Write));
    }

    public function testDeniedReadWrite(): void
    {
        $alc = (new AccessControl())
            ->addResource('Blog')
            ->deny('Blog', Permission::Read)
            ->deny('Blog', Permission::Write);

        $this->assertFalse($alc->isAllowed('Blog', Permission::Read));
        $this->assertFalse($alc->isAllowed('Blog', Permission::Write));
    }
}
