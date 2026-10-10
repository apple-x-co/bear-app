<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\CacheDemo;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;

/**
 * #[Cacheable(expiryAt: ...)] のサンプル
 *
 * 経過秒数ではなく、body に入れた日時（expires_at）までキャッシュする。
 * body に expiryAt で指定したキーが無いと ExpireAtKeyNotExists が投げられる。
 * 日時は strtotime() で解釈できる形式で返す。
 *
 * 確認方法（prod コンテキスト）:
 *   GET /cache-demo/expiry-at を繰り返す → expires_at までは generated_at が変わらない
 *   expires_at を過ぎたあとの GET     → 作り直されて generated_at と expires_at が新しくなる
 */
#[Cacheable(expiryAt: 'expires_at')]
class ExpiryAt extends ResourceObject
{
    public function onGet(): static
    {
        $now = new DateTimeImmutable();

        $this->body['generated_at'] = $now->format('H:i:s.u');
        $this->body['expires_at'] = $now->modify('+30 seconds')->format('c');

        return $this;
    }
}
