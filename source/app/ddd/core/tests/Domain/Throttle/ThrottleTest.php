<?php

declare(strict_types=1);

namespace AppCore\Domain\Throttle;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ThrottleTest extends TestCase
{
    public function testNotExceeded(): void
    {
        $throttle = new Throttle(
            'example',
            '127.0.0.1',
            0,
            10,
            '30 minutes',
            (new DateTimeImmutable()),
        );

        $this->assertFalse($throttle->isExceeded());
    }

    public function testExceeded(): void
    {
        $throttle = new Throttle(
            'example',
            '127.0.0.1',
            11,
            10,
            '30 minutes',
            (new DateTimeImmutable()),
        );

        $this->assertTrue($throttle->isExceeded());
    }

    public function testNotExpired(): void
    {
        $throttle = new Throttle(
            'example',
            '127.0.0.1',
            0,
            10,
            '30 minutes',
            (new DateTimeImmutable())->modify('+1 seconds'),
        );

        $this->assertFalse($throttle->isExpired());
    }

    public function testExpired(): void
    {
        $throttle = new Throttle(
            'example',
            '127.0.0.1',
            0,
            10,
            '30 minutes',
            (new DateTimeImmutable())->modify('-1 seconds'),
        );

        $this->assertTrue($throttle->isExpired());
    }

    public function testCountUp(): void
    {
        $throttle = (new Throttle(
            'example',
            '127.0.0.1',
            0,
            10,
            '30 minutes',
            (new DateTimeImmutable())->modify('-1 seconds'),
        ))->countUp('127.0.0.1');

        $this->assertSame(1, $throttle->iterationCount);
    }
}
