<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\ResourceInterface;
use MyVendor\MyProject\Injector;
use Override;
use PHPUnit\Framework\TestCase;

final class IndexTest extends TestCase
{
    private ResourceInterface $resource;

    #[Override]
    protected function setUp(): void
    {
        $injector = Injector::getInstance('app');
        $this->resource = $injector->getInstance(ResourceInterface::class);
    }

    public function testOnGet(): void
    {
        $ro = $this->resource->get('page://self/index', ['name' => 'BEAR.Sunday']);
        $this->assertSame(200, $ro->code);
        $this->assertSame('Hello BEAR.Sunday (page)', $ro->body['greeting']);
    }
}
