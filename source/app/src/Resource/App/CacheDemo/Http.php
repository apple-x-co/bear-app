<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\CacheDemo;

use BEAR\RepositoryModule\Annotation\HttpCache;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;

/**
 * #[HttpCache] のサンプル
 *
 * サーバー側にはキャッシュせず、レスポンスの Cache-Control ヘッダーだけを付ける（200 のときのみ）。
 * ブラウザは 5 分、CDN などの共有キャッシュは 1 時間キャッシュしてよい、という指示になる。
 *
 * 確認方法:
 *   GET /cache-demo/http → Cache-Control: max-age=300, s-maxage=3600 が付く。generated_at はリクエストごとに変わる
 *
 * @psalm-suppress ClassMustBeFinal BEAR.Sunday の AOP がリソースを継承して織り込むため final にしない
 */
#[HttpCache(maxAge: 300, sMaxAge: 3600)]
class Http extends ResourceObject
{
    public function onGet(): static
    {
        $this->body['generated_at'] = (new DateTimeImmutable())->format('H:i:s.u');

        return $this;
    }
}
