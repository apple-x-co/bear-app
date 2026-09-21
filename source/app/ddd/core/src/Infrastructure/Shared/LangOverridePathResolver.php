<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Attribute\LangOverrideDir;

use function is_file;

final readonly class LangOverridePathResolver
{
    public function __construct(
        #[LangOverrideDir]
        private string $langOverrideDir,
    ) {
    }

    public function findPath(string $relativePath): string|null
    {
        if ($this->langOverrideDir === '') {
            return null;
        }

        $path = $this->langOverrideDir . '/' . $relativePath;

        return is_file($path) ? $path : null;
    }
}
