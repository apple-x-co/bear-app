<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\CacheDemo;

use BEAR\RepositoryModule\Annotation\DonutCache;
use BEAR\RepositoryModule\Annotation\RefreshCache;
use BEAR\Resource\Annotation\Embed;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;

/**
 * #[DonutCache] のサンプル
 *
 * ページ全体はキャッシュされず、ETag も付かない。キャッシュされるのは「ドーナツ部分」(generated_at) だけで、
 * 埋め込んだ clock（穴）はリクエストのたびに評価される。
 *
 * 確認方法（prod コンテキスト）:
 *   GET /cache-demo/donut を繰り返す → generated_at は変わらず、clock の時刻だけ変わる
 *   PUT /cache-demo/donut           → #[RefreshCache] でキャッシュが更新され、generated_at が変わる
 */
#[DonutCache]
class Donut extends ResourceObject
{
    #[Embed(rel: 'clock', src: 'page://self/cache-demo/clock')]
    public function onGet(): static
    {
        $this->body['generatedAt'] = (new DateTimeImmutable())->format('H:i:s.u');

        return $this;
    }

    #[RefreshCache]
    public function onPut(): static
    {
        return $this;
    }
}
