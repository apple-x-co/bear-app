<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\CacheDemo;

use BEAR\Resource\ResourceObject;
use DateTimeImmutable;

/**
 * ドーナツキャッシュの「穴」になるリソース
 *
 * キャッシュ系のアトリビュートを付けていないので、リクエストのたびに現在時刻を返す。
 */
class Clock extends ResourceObject
{
    public function onGet(): static
    {
        $this->body['now'] = (new DateTimeImmutable())->format('H:i:s.u');

        return $this;
    }
}
