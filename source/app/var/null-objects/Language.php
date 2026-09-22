<?php

declare(strict_types=1);

namespace AppCore\Domain\Language;

final class Language implements LanguageInterface
{
    public function get(string $key, array $params = []): string
    {
        return 'DUMMY';
    }
}
