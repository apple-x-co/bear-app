<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Language\LanguageFactoryInterface;
use AppCore\Domain\Language\LanguageInterface;
use AppCore\Domain\Locale\Locale;
use Override;
use Ray\Di\ProviderInterface;

/** @template-implements ProviderInterface<LanguageInterface> */
final readonly class JapaneseProvider implements ProviderInterface
{
    public function __construct(
        private LanguageFactoryInterface $languageFactory,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress UnresolvableInclude
     */
    #[Override]
    public function get()
    {
        return $this->languageFactory->create(Locale::Japanese);
    }
}
