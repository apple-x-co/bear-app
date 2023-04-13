<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Input\Builder;
use Aura\Input\Filter;
use MyVendor\MyProject\Form\Fieldset\AddressFieldset;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminMultipleDemoForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'multipleDemo';

    protected function getFormName(): string
    {
        return self::FORM_NAME;
    }

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        $this->builder = new Builder([
            'address' => static function () {
                return new AddressFieldset(
                    new Builder(),
                    new Filter(),
                );
            },
        ]);

        $this->setCollection('addresses', 'address');
        /** @psalm-suppress UndefinedMethod */
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('addresses')
            ->is('callback', static function (stdClass $subject, string $field) {
                /** @var array<int, array{zip?: string|null, state?: string|null, city?: string|null, street?: string|null}> $addresses */
                $addresses = $subject->$field;

                foreach ($addresses as $address) {
                    if (! isset($address['zip'], $address['state'], $address['city'], $address['street'])) {
                        return false;
                    }

                    if ($address['zip'] === '' || $address['state'] === '' || $address['city'] === '' || $address['street'] === '') {
                        return false;
                    }
                }

                return true;
            });

        /** @psalm-suppress UndefinedMethod */
        $this->setField('submit', 'submit')
             ->setAttribs(['tabindex' => 99]);
    }
}
