<?php

declare(strict_types=1);

namespace AppCore\Domain;

use PHPUnit\Framework\TestCase;

use function random_bytes;
use function strlen;

class SecureRandomTest extends TestCase
{
    public function testHash1(): void
    {
        $plainText = 'HELLO WORLD';
        $secureRandom = new SecureRandom(random_bytes(32));
        $hashed = $secureRandom->hash($plainText);
        $this->assertNotEquals($plainText, $hashed);
    }

    public function testHash2(): void
    {
        $plainText = 'HELLO WORLD';
        $secureRandom = new SecureRandom(random_bytes(32));
        $hashed = $secureRandom->hash($plainText);
        $this->assertNotEquals($plainText, $hashed);
    }

    public function testHmac1(): void
    {
        $plainText = 'HELLO WORLD';
        $secureRandom = new SecureRandom(random_bytes(32));
        $hashed = $secureRandom->hmac($plainText);
        $this->assertNotEquals($plainText, $hashed);
    }

    public function testHmac2(): void
    {
        $plainText = 'HELLO WORLD';
        $secureRandom = new SecureRandom(random_bytes(32));
        $hashed = $secureRandom->hmac($plainText);
        $this->assertNotEquals($plainText, $hashed);
    }

    public function testRandomBytes(): void
    {
        $secureRandom = new SecureRandom(random_bytes(32));
        $bytes = $secureRandom->randomBytes(10);
        $this->assertEquals(10, strlen($bytes));
    }
}
