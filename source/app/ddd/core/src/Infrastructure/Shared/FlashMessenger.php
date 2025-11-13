<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Attribute\Cookie;
use AppCore\Attribute\SessionName;
use AppCore\Domain\FlashMessenger\FlashMessageType;
use AppCore\Domain\FlashMessenger\FlashMessengerInterface;
use AppCore\Exception\RuntimeException;

use function session_id;
use function session_start;
use function session_write_close;

/** @SuppressWarnings("PHPMD.Superglobals") */
final readonly class FlashMessenger implements FlashMessengerInterface
{
    /** @param array<string, string> $cookie */
    public function __construct(
        #[Cookie]
        private array $cookie,
        #[SessionName]
        private string $sessionName,
    ) {
    }

    public function set(FlashMessageType $type, string $text): void
    {
        $this->resume();
        $_SESSION[$this::class][$type->value] = $text;
        $this->commit();
    }

    public function get(FlashMessageType $type, string|null $alternative = null): string|null
    {
        $this->resume();

        if (isset($_SESSION[$this::class][$type->value])) {
            $text = $_SESSION[$this::class][$type->value];
            unset($_SESSION[$this::class][$type->value]);

            return $text;
        }

        return $alternative;
    }

    private function start(): bool
    {
        return session_start();
    }

    private function resume(): void
    {
        if (session_id() !== '') {
            return;
        }

        if (! isset($this->cookie[$this->sessionName])) {
            return;
        }

        $bool = $this->start();
        if ($bool) {
            return;
        }

        throw new RuntimeException('Can\'t start "FlashMessenger" session');
    }

    private function commit(): void
    {
        if (session_id() === '') {
            return;
        }

        session_write_close();
    }
}
