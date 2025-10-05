<?php

declare(strict_types=1);

namespace AppCore\Domain\WebSignature;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

use function date_default_timezone_set;

class UrlSignatureTest extends TestCase
{
    public function testSerialize(): void
    {
        date_default_timezone_set('Asia/Tokyo');

        $serialized = (new UrlSignature(
            (new DateTimeImmutable('2023-01-01 00:00:00')),
            'test@example.com',
        ))->serialize('abc');

        $this->assertSame(
            'a:3:{s:1:"_";s:3:"abc";s:9:"timestamp";i:1672498800;s:7:"address";s:16:"test@example.com";}',
            $serialized
        );
    }

    public function testDeserialize(): void
    {
        date_default_timezone_set('Asia/Tokyo');

        $deserialized = UrlSignature::deserialize(
            'a:3:{s:1:"_";s:3:"abc";s:9:"timestamp";i:1672498800;s:7:"address";s:16:"test@example.com";}'
        );

        $this->assertSame('2023-01-01 00:00:00', $deserialized->expiresDate->format('Y-m-d H:i:s'));
        $this->assertSame('test@example.com', $deserialized->address);
    }
}
