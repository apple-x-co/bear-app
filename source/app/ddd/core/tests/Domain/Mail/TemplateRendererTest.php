<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

use PHPUnit\Framework\TestCase;

class TemplateRendererTest extends TestCase
{
    public function testRender1(): void
    {
        $path = __DIR__ . '/HELLO1.txt';
        $this->assertEquals("HELLO\n", (new TemplateRenderer('Test'))($path));
    }

    public function testRender2(): void
    {
        $path = __DIR__ . '/HELLO2.txt';
        $this->assertEquals("HELLO WORLD!!\n", (new TemplateRenderer('Test'))($path, ['name' => 'WORLD!!']));
    }
}
