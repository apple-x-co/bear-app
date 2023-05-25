<?php

declare(strict_types=1);

namespace AppCore\Domain;

use AppCore\Infrastructure\Shared\Encrypter;
use PHPUnit\Framework\TestCase;

class EncrypterTest extends TestCase
{
    public function testEncrypt1(): void
    {
        $plainText = 'HELLO';
        $encrypter = new Encrypter('ABCDEFGHIJKLMNO'); // 16bytes
        $encrypted = $encrypter->encrypt($plainText);
        $this->assertNotEquals($plainText, $encrypted);
    }

    public function testEncrypt2(): void
    {
        $plainText = 'HELLO';
        $encrypter = new Encrypter('ABCDEFGHIJKLMNO'); // 16bytes
        $encrypted1 = $encrypter->encrypt($plainText);
        $encrypted2 = $encrypter->encrypt($plainText);
        $this->assertNotEquals($encrypted1, $encrypted2);
    }

    public function testDecrypt(): void
    {
        $encrypted = 'eyJpdiI6IkxVeVdCRU1VdWM1VkVkWUZvZTNpRXc9PSIsImhtYWMiOiI2N2JhNjllZmQyMTE1Yjc3OTE0OGRjNTRkMGM4ZWU3OGQ1MGIyMjE1NzNmNDU1ZmY4YjY3NTNjMDcyYjk3M2YyIiwiZW5jcnlwdGVkIjoicXBQSUx3UE8yS0JtR3VEVVExQ2lodz09In0=';
        $encrypter = new Encrypter('ABCDEFG');
        $decrypted = $encrypter->decrypt($encrypted);
        $this->assertEquals(
            'HELLO',
            $decrypted,
        );
    }
}
