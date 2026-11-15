<?php

declare(strict_types=1);

namespace AppCore\Domain\Auth;

use AppCore\Domain\AccessControl\AccessControl;

interface AdminAuthenticatorInterface
{
    public function login(string $username, string $password): void;

    /** ログインし、Remember me の Cookie を発行する */
    public function rememberLogin(string $username, string $password): void;

    public function verifyPassword(string $username, string $password): void;

    public function logout(): void;

    /** ログイン済みか（パスワードでのログインと、Remember me の Cookie からの復帰のどちらも含む） */
    public function isValid(): bool;

    /** Remember me の Cookie から復帰したか（このセッションではパスワードを入力していない） */
    public function isRemembered(): bool;

    public function isExpired(): bool;

    public function getUserName(): string|null;

    /** @return positive-int|null */
    public function getUserId(): int|null;

    public function getDisplayName(): string|null;

    /**
     * @return array{id?: string, display_name?: string}
     *
     * @internal
     */
    public function getUserData(): array;

    public function getAuthRedirect(): string;

    public function getUnauthRedirect(): string;

    public function getPasswordRedirect(): string;

    public function getIdentity(): AdminIdentity;

    public function getAccessControl(): AccessControl;
}
