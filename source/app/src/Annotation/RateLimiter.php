<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class RateLimiter
{
    public int $limit = 10;

    /** @see https://www.php.net/datetime.formats.relative */
    public string $interval = '30 minutes';
}
