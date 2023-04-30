<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

interface AdminAuthenticatorInterface
{
    public function login(string $username, string $password, ?string $remoteIp = null): void;

    public function rememberLogin(string $username, string $password, ?string $remoteIp = null): void;

    public function getRememberCookieName(): string;

    public function continueLogin(string $payload): bool;

    public function verifyPassword(string $username, string $password): void;

    public function logout(): void;

    public function isValid(): bool;

    public function isExpired(): bool;

    public function getUserName(): ?string;

    /**
     * @return array<string, scalar>
     */
    public function getUserData(): array;

    public function getAuthRedirect(): string;

    public function getUnauthRedirect(): string;

    public function getPasswordRedirect(): string;
}
