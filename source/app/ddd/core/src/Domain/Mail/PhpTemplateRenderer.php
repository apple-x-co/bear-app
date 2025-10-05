<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

use AppCore\Attribute\ServiceName;
use AppCore\Exception\RuntimeException;
use DateTimeImmutable;
use InvalidArgumentException;
use Throwable;

use function array_merge;
use function extract;
use function file_exists;
use function is_readable;
use function ob_end_clean;
use function ob_get_clean;
use function ob_start;
use function realpath;

use const EXTR_SKIP;

readonly class PhpTemplateRenderer implements TemplateRendererInterface
{
    public function __construct(
        #[ServiceName]
        private string $serviceName,
    ) {
    }

    /** @param array<string, mixed> $vars */
    public function __invoke(string $path, array $vars = []): string
    {
        $path = realpath($path);
        if ($path === false) {
            throw new InvalidArgumentException('Invalid template path provided');
        }

        if (! file_exists($path)) {
            throw new InvalidArgumentException("Template file not found: {$path}");
        }

        if (! is_readable($path)) {
            throw new InvalidArgumentException("Template file not readable: {$path}");
        }

        $defaultVars = [
            'now' => new DateTimeImmutable(),
            'serviceName' => $this->serviceName,
        ];

        $vars = array_merge($defaultVars, $vars);

        try {
            ob_start();
            extract($vars, EXTR_SKIP); // phpcs:ignore
            include $path;

            return (string) ob_get_clean();
        } catch (Throwable $throwable) {
            ob_end_clean();

            throw new RuntimeException(
                "Template rendering failed for: {$path}. Error: " . $throwable->getMessage(),
                0,
                $throwable,
            );
        }
    }
}
