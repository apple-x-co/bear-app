<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Attribute\LangDir;
use AppCore\Domain\Language\Language;
use AppCore\Domain\Language\LanguageFactoryInterface;
use AppCore\Domain\Language\LanguageInterface;
use AppCore\Domain\Locale\Locale;

use function array_merge;
use function is_array;

final readonly class LanguageFactory implements LanguageFactoryInterface
{
    private const string FILE_EXT = '.php';

    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        #[LangDir]
        private string $langDir,
        private LangOverridePathResolver $langOverridePathResolver,
    ) {
    }

    public function create(Locale $locale): LanguageInterface
    {
        $texts = $this->load($locale);
        $fallbacks = $locale === Locale::Japanese ? [] : $this->load(Locale::Japanese);

        return new Language($texts, $fallbacks);
    }

    /** @return array<string, string> */
    private function load(Locale $locale): array
    {
        /** @psalm-suppress UnresolvableInclude */
        $texts = require $this->langDir . '/' . $locale->value . self::FILE_EXT;
        if (! is_array($texts)) {
            return [];
        }

        $path = $this->langOverridePathResolver->findPath($locale->value . self::FILE_EXT);
        if ($path === null) {
            return $texts;
        }

        /** @psalm-suppress UnresolvableInclude */
        $overrideTexts = require $path;
        if (! is_array($overrideTexts)) {
            return $texts;
        }

        return array_merge($texts, $overrideTexts);
    }
}
