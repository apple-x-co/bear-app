<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Attribute\LangDir;
use AppCore\Domain\Document\DocumentReaderInterface;
use AppCore\Domain\Locale\Locale;

use function file_get_contents;
use function is_file;

final readonly class DocumentReader implements DocumentReaderInterface
{
    private const string DOCUMENT_DIR_NAME = 'documents';
    private const string DOCUMENT_DEFAULT = '';
    private const string FILE_EXT = '.html';

    public function __construct(
        #[LangDir]
        private string $langDir,
        private LangOverridePathResolver $langOverridePathResolver,
    ) {
    }

    public function read(string $name, Locale $locale): string
    {
        $content = $this->readForLocale($name, $locale);
        if ($content !== null) {
            return $content;
        }

        if ($locale === Locale::Japanese) {
            return self::DOCUMENT_DEFAULT;
        }

        return $this->readForLocale($name, Locale::Japanese) ?? self::DOCUMENT_DEFAULT;
    }

    private function readForLocale(string $name, Locale $locale): string|null
    {
        $relativePath = self::DOCUMENT_DIR_NAME . '/' . $name . '.' . $locale->value . self::FILE_EXT;

        $overridePath = $this->langOverridePathResolver->findPath($relativePath);
        if ($overridePath !== null) {
            return (string) file_get_contents($overridePath);
        }

        $path = $this->langDir . '/' . $relativePath;
        if (is_file($path)) {
            return (string) file_get_contents($path);
        }

        return null;
    }
}
