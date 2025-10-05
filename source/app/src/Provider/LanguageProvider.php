<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Attribute\LangDir;
use AppCore\Domain\Language\Language;
use Ray\Di\ProviderInterface;

/** @template-implements ProviderInterface<Language> */
readonly class LanguageProvider implements ProviderInterface
{
    public function __construct(
        #[LangDir]
        private string $langDir,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress UnresolvableInclude
     */
    public function get()
    {
        // TODO: header から言語を判断する

        return new Language(require $this->langDir . '/ja/messages.php');
    }
}
