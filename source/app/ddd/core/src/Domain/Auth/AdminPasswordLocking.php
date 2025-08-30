<?php

declare(strict_types=1);

namespace AppCore\Domain\Auth;

enum AdminPasswordLocking
{
    case Locked;
    case Unlocked;
}
