<?php

declare(strict_types=1);

namespace AppCore\Domain\ResetPassword;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

use function date_default_timezone_set;

class ResetPasswordSignatureTest extends TestCase
{
    public function testSerialize(): void
    {
        date_default_timezone_set('Asia/Tokyo');

        $serialized = (new ResetPasswordSignature(
            'abc',
            (new DateTimeImmutable('2023-01-01 00:00:00')),
            'test@example.com',
        ))->serialize();

        $this->assertSame(
            'a:3:{s:1:"_";s:3:"abc";s:9:"timestamp";i:1672498800;s:7:"address";s:16:"test@example.com";}',
            $serialized
        );
    }

    public function testDeserialize(): void
    {
        date_default_timezone_set('Asia/Tokyo');

        $deserialized = ResetPasswordSignature::deserialize(
            'a:3:{s:1:"_";s:3:"abc";s:9:"timestamp";i:1672498800;s:7:"address";s:16:"test@example.com";}'
        );

        $this->assertSame('2023-01-01 00:00:00', $deserialized->expiresAt->format('Y-m-d H:i:s'));
        $this->assertSame('test@example.com', $deserialized->address);
    }
}
