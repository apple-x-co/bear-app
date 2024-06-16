<?php

declare(strict_types=1);

namespace AppCore\Presentation\Shared;

interface AdminContextInterface
{
    public function isAllowed(string $resourceName, string $permission): bool;

    public function displayName(): string;

    public function flashMessage(): string|null;

    public function setFlashMessage(string $message): void;

    public function setSessionValue(string $key, string $val): void;

    public function getSessionValue(string $key, ?string $alt = null): mixed;

    public function resetSessionValue(string $key): void;
}
