<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\CacheDemo;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;

/**
 * #[Purge] / #[Refresh] の対象になるキャッシュ（QuoteCommand から無効化される）
 *
 * id ごとに別々にキャッシュされる（URI とクエリがキャッシュキーになる）。
 *
 * @psalm-suppress ClassMustBeFinal BEAR.Sunday の AOP がリソースを継承して織り込むため final にしない
 */
#[Cacheable(expirySecond: 3600)]
class Quote extends ResourceObject
{
    public function onGet(int $id = 1): static
    {
        $this->body['id'] = $id;
        $this->body['generated_at'] = (new DateTimeImmutable())->format('H:i:s.u');

        return $this;
    }
}
