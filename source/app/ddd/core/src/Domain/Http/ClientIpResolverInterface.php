<?php

declare(strict_types=1);

namespace AppCore\Domain\Http;

interface ClientIpResolverInterface
{
    /** リクエスト元のクライアント IP を返す。取得できない（CLI など）ときは null */
    public function resolve(): string|null;
}
