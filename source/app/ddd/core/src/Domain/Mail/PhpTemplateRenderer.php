<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

use AppCore\Attribute\ServiceName;
use AppCore\Exception\RuntimeException;
use DateTimeImmutable;
use InvalidArgumentException;
use Override;
use Throwable;

use function array_merge;
use function extract;
use function file_exists;
use function htmlspecialchars;
use function is_readable;
use function nl2br;
use function ob_end_clean;
use function ob_get_clean;
use function ob_start;
use function realpath;
use function trim;

use const EXTR_SKIP;

readonly class PhpTemplateRenderer implements TemplateRendererInterface
{
    public function __construct(
        #[ServiceName]
        private string $serviceName,
    ) {
    }

    /** @param array<string, mixed> $vars */
    #[Override]
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
            '__NOW__' => static fn (): DateTimeImmutable => new DateTimeImmutable(),
            '__SERVICE_NAME__' => $this->serviceName,
        ];

        $vars = array_merge($defaultVars, $vars);

        try {
            ob_start();
            extract($vars, EXTR_SKIP); // phpcs:ignore
            include $path;

            return trim((string) ob_get_clean());
        } catch (Throwable $throwable) {
            ob_end_clean();

            throw new RuntimeException(
                "Template rendering failed for: {$path}. Error: " . $throwable->getMessage(),
                0,
                $throwable,
            );
        }
    }

    /**
     * テンプレートから `$this->h()` で呼び出す HTML エスケープ
     *
     * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
     * @SuppressWarnings("PHPMD.ShortMethodName")
     * @phpstan-ignore method.unused (テンプレートから呼び出すため静的解析では検出できない)
     */
    private function h(string|null $value): string
    {
        return htmlspecialchars($value ?? '');
    }

    /**
     * テンプレートから `$this->nl2br()` で呼び出す 改行コードを br タグ変換
     *
     * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
     * @phpstan-ignore method.unused (テンプレートから呼び出すため静的解析では検出できない)
     */
    private function nl2br(string|null $value): string
    {
        return nl2br($value ?? '');
    }
}
