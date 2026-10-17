<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer\Fieldset;

use Aura\Input\Builder;
use PHPUnit\Framework\TestCase;

use function array_keys;

class AddressFilterTest extends TestCase
{
    private AddressFieldset $fieldset;

    protected function setUp(): void
    {
        $this->fieldset = new AddressFieldset(new Builder(), new AddressFilter());
    }

    public function testOmittedFieldsAreRejected(): void
    {
        $this->fieldset->fill([]);

        $this->assertFalse($this->fieldset->filter());
        $this->assertEqualsCanonicalizing(
            ['zip', 'state', 'city', 'street', 'houseType', 'smartphones'],
            array_keys($this->fieldset->getMessages()),
        );
    }

    public function testNonStringZipIsRejectedWithoutError(): void
    {
        $this->fieldset->fill($this->validAddress(['zip' => ['1000001']]));

        $this->assertFalse($this->fieldset->filter());
        $this->assertSame(['zip'], array_keys($this->fieldset->getMessages()));
    }

    public function testCompleteAddressPasses(): void
    {
        $this->fieldset->fill($this->validAddress());

        $this->assertTrue($this->fieldset->filter());
    }

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private function validAddress(array $overrides = []): array
    {
        return $overrides + [
            'zip' => '1000001',
            'state' => 'Tokyo',
            'city' => 'Chiyoda',
            'street' => '1-1',
            'houseType' => '1',
            'smartphones' => ['iphone15'],
        ];
    }
}
