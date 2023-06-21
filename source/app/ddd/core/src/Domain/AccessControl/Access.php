<?php

declare(strict_types=1);

namespace AppCore\Domain\AccessControl;

enum Access
{
    case Allow;
    case Deny;

    public static function from(string $string): self
    {
        return match ($string) {
            self::Allow->name => self::Allow,
            self::Deny->name => self::Deny,
            default => throw new AccessNotMatchException($string),
        };
    }
}
