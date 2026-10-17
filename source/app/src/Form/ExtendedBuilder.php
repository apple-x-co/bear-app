<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Input\Builder;

/** @SuppressWarnings("PHPMD.CamelCasePropertyName") */
class ExtendedBuilder extends Builder
{
    /**
     * Aura\Input\Builder 側で決まっているプロパティ名
     *
     * @var string
     */
    // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
    protected $collection_class = ExtendedCollection::class;
}
