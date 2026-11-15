<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

use Override;

class EmailConfig implements EmailConfigInterface
{
    /** @param array{ssl?: array{verify_peer: bool, verify_peer_name: bool, allow_self_signed: bool}} $options */
    public function __construct(
        private readonly string $hostname,
        private readonly int $port,
        private readonly string $username,
        private readonly string $password,
        private readonly array $options,
    ) {
    }

    #[Override]
    public function getHostname(): string
    {
        return $this->hostname;
    }

    #[Override]
    public function getPort(): int
    {
        return $this->port;
    }

    #[Override]
    public function getUsername(): string
    {
        return $this->username;
    }

    #[Override]
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getOptions(): array
    {
        return $this->options;
    }
}
