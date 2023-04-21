<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

enum AdminPasswordLocking
{
    case Locked;
    case Unlocked;
}
