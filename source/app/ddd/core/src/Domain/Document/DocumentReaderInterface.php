<?php

declare(strict_types=1);

namespace AppCore\Domain\Document;

use AppCore\Domain\Locale\Locale;

interface DocumentReaderInterface
{
    public function read(string $name, Locale $locale): string;
}
