<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Language\LangDir;
use AppCore\Domain\Language\Language;
use Ray\Di\ProviderInterface;

class LanguageProvider implements ProviderInterface
{
    public function __construct(
        #[LangDir] private readonly string $langDir,
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
