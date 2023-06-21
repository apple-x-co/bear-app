<?php

declare(strict_types=1);

namespace AppCore\Domain\AccessControl;

enum Permission
{
    case Privilege;
    case Read;
    case Write;

    public static function from(string $string): self
    {
        return match ($string) {
            self::Privilege->name => self::Privilege,
            self::Read->name => self::Read,
            self::Write->name => self::Write,
            default => throw new PermissionNotMatchException($string),
        };
    }
}
