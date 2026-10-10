<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\CacheDemo;

use BEAR\RepositoryModule\Annotation\CacheableResponse;
use BEAR\Resource\Annotation\Embed;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;

/**
 * #[CacheableResponse] ＋ #[Embed] のサンプル（依存関係による無効化）
 *
 * 埋め込んだ comment もキャッシュ可能なので、Article 全体がキャッシュされて ETag が付く。
 * comment が更新される（PUT /cache-demo/comment）と、Article の ETag も一緒に更新される。
 * このとき変更のない Article 側のキャッシュは再利用され、更新された comment 部分だけが差し替わる。
 *
 * 確認方法（prod コンテキスト）:
 *   GET /cache-demo/article を繰り返す → ETag も 2 つの generatedAt も変わらない
 *   PUT /cache-demo/comment           → 次の GET で ETag と comment の generatedAt が変わる（Article 側は据え置き）
 *   PUT /cache-demo/article           → 次の GET で Article の generatedAt が変わる（comment 側は据え置き）
 */
#[CacheableResponse]
class Article extends ResourceObject
{
    #[Embed(rel: 'comment', src: 'page://self/cache-demo/comment')]
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
