<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

enum FormStep
{
    case Input;
    case Confirm;
    case Complete;
}
