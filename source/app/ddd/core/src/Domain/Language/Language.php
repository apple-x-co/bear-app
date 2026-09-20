<?php

declare(strict_types=1);

namespace AppCore\Domain\Language;

use function str_replace;

readonly class Language implements LanguageInterface
{
    /**
     * @param array<string, string> $texts
     * @param array<string, string> $fallbacks
     */
    public function __construct(
        private array $texts,
        private array $fallbacks,
    ) {
    }

    /** @inheritDoc */
    public function get(string $key, array $params = []): string
    {
        $text = $this->texts[$key] ?? ($this->fallbacks[$key] ?? $key);

        foreach ($params as $paramName => $paramValue) {
            $text = str_replace('{' . $paramName . '}', (string) $paramValue, $text);
        }

        return $text;
    }
}
