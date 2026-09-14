<?php

declare(strict_types=1);

namespace AppCore\Domain\Locale;

use PHPUnit\Framework\TestCase;

class LocaleTest extends TestCase
{
    public function testTryFromPathWithTrailingSegment(): void
    {
        $this->assertSame(Locale::Japanese, Locale::tryFromPath('/ja/topics/detail'));
        $this->assertSame(Locale::English, Locale::tryFromPath('/en/topics/detail'));
    }

    public function testTryFromPathExactMatch(): void
    {
        $this->assertSame(Locale::Japanese, Locale::tryFromPath('/ja'));
        $this->assertSame(Locale::English, Locale::tryFromPath('/en'));
    }

    public function testTryFromPathReturnsNullWhenNoLocalePrefix(): void
    {
        $this->assertNull(Locale::tryFromPath('/'));
        $this->assertNull(Locale::tryFromPath('/admin/xxxx'));
        $this->assertNull(Locale::tryFromPath('/jaxx'));
    }

    public function testStripPrefixFromPathWithTrailingSegment(): void
    {
        $this->assertSame('/topics/detail', Locale::stripPrefixFromPath('/ja/topics/detail'));
        $this->assertSame('/topics/detail', Locale::stripPrefixFromPath('/en/topics/detail'));
    }

    public function testStripPrefixFromPathExactMatch(): void
    {
        $this->assertSame('/', Locale::stripPrefixFromPath('/ja'));
        $this->assertSame('/', Locale::stripPrefixFromPath('/en'));
    }

    public function testStripPrefixFromPathReturnsUnchangedWhenNoLocalePrefix(): void
    {
        $this->assertSame('/', Locale::stripPrefixFromPath('/'));
        $this->assertSame('/admin/xxxx', Locale::stripPrefixFromPath('/admin/xxxx'));
    }
}
