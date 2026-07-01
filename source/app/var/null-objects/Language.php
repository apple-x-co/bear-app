<?php

declare(strict_types=1);

namespace AppCore\Domain\Language;

use function var_dump;

final class Language implements LanguageInterface
{
    public function __construct()
    {
        var_dump(__FILE__);
    }

    public function get(string $key, array $params = []): string
    {
        return $key;
    }
}
