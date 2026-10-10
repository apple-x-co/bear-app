<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\CacheDemo;

use BEAR\RepositoryModule\Annotation\CacheableResponse;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;

/**
 * #[CacheableResponse] のサンプル（Article に埋め込まれる側）
 *
 * クラスに付けると、onGet の結果が全体キャッシュされて ETag が付く。
 * 有効期限（TTL）はなく、onPut / onPatch / onDelete / onPost が実行されたときにキャッシュが更新される。
 */
#[CacheableResponse]
class Comment extends ResourceObject
{
    public function onGet(): static
    {
        $this->body['generatedAt'] = (new DateTimeImmutable())->format('H:i:s.u');

        return $this;
    }

    public function onPut(): static
    {
        return $this;
    }
}
