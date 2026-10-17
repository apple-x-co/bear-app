<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Filter\FilterFactory;
use Aura\Html\HelperLocatorFactory;
use Aura\Input\Builder;
use Aura\Input\Exception\NoSuchInput;
use MyVendor\MyProject\Form\Fixture\NestedDemoForm;
use PHPUnit\Framework\TestCase;

use function array_keys;

class ExtendedFormTest extends TestCase
{
    private NestedDemoForm $form;

    protected function setUp(): void
    {
        $this->form = new NestedDemoForm();
        $this->form->setBaseDependencies(new Builder(), new FilterFactory(), new HelperLocatorFactory());
        $this->form->postConstruct();
    }

    /** @return array<string, mixed> */
    private static function validData(): array
    {
        return [
            'note' => 'hello',
            'home' => ['zip' => '1000001', 'geo' => ['lat' => '35']],
            'deliveries' => [
                ['zip' => '1000002', 'geo' => ['lat' => '36']],
                ['zip' => '1000003', 'geo' => ['lat' => '37']],
            ],
        ];
    }

    public function testApplyPassesAndGetValueReturnsNestedArray(): void
    {
        $this->assertTrue($this->form->apply(self::validData()));
        $this->assertTrue($this->form->isSuccess());

        $value = $this->form->getValue();
        $this->assertSame('1000001', $value['home']['zip']);
        $this->assertSame('37', $value['deliveries'][1]['geo']['lat']);
    }

    public function testFieldsetFailureIsReportedByPath(): void
    {
        $data = self::validData();
        $data['home']['zip'] = 'abc';

        $this->assertFalse($this->form->apply($data));
        $this->assertSame('Zip is digit only.', $this->form->error('home[zip]'));
        $this->assertSame('', $this->form->error('deliveries[0][zip]'));
    }

    public function testOnlyFailedCollectionRowIsReported(): void
    {
        $data = self::validData();
        $data['deliveries'][1]['zip'] = 'x';

        $this->assertFalse($this->form->apply($data));
        $this->assertSame('', $this->form->error('deliveries[0][zip]'));
        $this->assertSame('Zip is digit only.', $this->form->error('deliveries[1][zip]'));
    }

    public function testGrandchildFailureIsReported(): void
    {
        $data = self::validData();
        $data['home']['geo']['lat'] = '';
        $data['deliveries'][0]['geo']['lat'] = '';

        $this->assertFalse($this->form->apply($data));
        $this->assertSame('Lat is required.', $this->form->error('home[geo][lat]'));
        $this->assertSame('Lat is required.', $this->form->error('deliveries[0][geo][lat]'));
        $this->assertSame('', $this->form->error('deliveries[1][geo][lat]'));
    }

    public function testTopLevelAndNestedFailuresAreCollectedTogether(): void
    {
        $data = self::validData();
        $data['note'] = '';
        $data['home']['zip'] = 'abc';

        $this->assertFalse($this->form->apply($data));
        $this->assertSame('Note is required.', $this->form->error('note'));
        $this->assertSame('Zip is digit only.', $this->form->error('home[zip]'));
        $this->assertSame(['note', 'home[zip]'], array_keys($this->form->getFailureMessages()));
    }

    public function testMalformedInputDoesNotThrow(): void
    {
        $data = [
            'note' => 'hello',
            'home' => 'string',
            'deliveries' => [
                'string',
                ['zip' => '1', 'unknown' => 'x', 'geo' => ['lat' => '1']],
            ],
            'unknown' => 'x',
        ];

        $this->assertFalse($this->form->apply($data));
        $this->assertSame('Zip is digit only.', $this->form->error('home[zip]'));
        $this->assertSame([1], $this->form->get('deliveries')->getKeys());
    }

    public function testErrorBeforeApplyIsEmptyAndRefreshedAfterApply(): void
    {
        $this->assertSame('', $this->form->error('home[zip]'));

        $data = self::validData();
        $data['home']['zip'] = 'abc';
        $this->form->apply($data);

        $this->assertSame('Zip is digit only.', $this->form->error('home[zip]'));
    }

    public function testInputResolvesNestedPath(): void
    {
        $this->form->apply(self::validData());

        $this->assertStringContainsString('name="home[zip]"', $this->form->input('home[zip]'));
        $this->assertStringContainsString('name="deliveries[0][zip]"', $this->form->input('deliveries[0][zip]'));
        $this->assertStringContainsString('name="deliveries[1][geo][lat]"', $this->form->input('deliveries[1][geo][lat]'));
        $this->assertStringContainsString('name="note"', $this->form->input('note'));
    }

    public function testInputWithUnknownPathThrows(): void
    {
        $this->expectException(NoSuchInput::class);

        $this->form->input('deliveries[9][zip]');
    }
}
