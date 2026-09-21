<?php

declare(strict_types=1);

namespace AppCore\Domain\Language;

use AppCore\Domain\Locale\Locale;

interface LanguageFactoryInterface
{
    public function create(Locale $locale): LanguageInterface;
}
