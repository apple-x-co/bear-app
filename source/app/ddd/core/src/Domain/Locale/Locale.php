<?php

declare(strict_types=1);

namespace AppCore\Domain\Locale;

use function str_starts_with;
use function strlen;
use function substr;

enum Locale: string
{
    case Japanese = 'ja';
    case English = 'en';

    /**
     * パス先頭が /xx/ または /xx で終わるロケール接頭辞に一致するか判定する
     */
    public static function tryFromPath(string $path): self|null
    {
        foreach (self::cases() as $locale) {
            if (str_starts_with($path, '/' . $locale->value . '/') || $path === '/' . $locale->value) {
                return $locale;
            }
        }

        return null;
    }

    public static function stripPrefixFromPath(string $path): string
    {
        $locale = self::tryFromPath($path);
        if ($locale === null) {
            return $path;
        }

        if ($path === '/' . $locale->value) {
            return '/';
        }

        return substr($path, strlen('/' . $locale->value));
    }
}
