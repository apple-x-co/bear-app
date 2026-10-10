<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\CacheDemo;

use BEAR\RepositoryModule\Annotation\Purge;
use BEAR\RepositoryModule\Annotation\Refresh;
use BEAR\Resource\ResourceObject;

/**
 * #[Purge] / #[Refresh] のサンプル
 *
 * #[Cacheable] ではないクラスの書き込みメソッドに付けると、メソッド成功後に指定した URI のキャッシュを無効化する。
 * uri は URI テンプレートで、{id} などにはリクエストのクエリ（と body）の値が入る。
 *
 * 確認方法（prod コンテキスト）:
 *   GET    /cache-demo/quote?id=1        → キャッシュされる（generated_at は変わらない）
 *   DELETE /cache-demo/quote-command?id=1 → #[Purge]: キャッシュを破棄。次の GET で作り直される
 *   PUT    /cache-demo/quote-command?id=1 → #[Refresh]: キャッシュを破棄して、その場で GET し直して保存する
 */
class QuoteCommand extends ResourceObject
{
    /** @SuppressWarnings("PHPMD.UnusedFormalParameter") */
    #[Purge(uri: 'app://self/cache-demo/quote?id={id}')]
    public function onDelete(int $id): static
    {
        return $this;
    }

    /** @SuppressWarnings("PHPMD.UnusedFormalParameter") */
    #[Refresh(uri: 'app://self/cache-demo/quote?id={id}')]
    public function onPut(int $id): static
    {
        return $this;
    }
}
