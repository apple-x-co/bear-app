<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

/** ALB の前に置いている CDN（環境変数 CDN_PROVIDER_NAME の値） */
enum CdnProvider: string
{
    case Amazon = 'amazon';
    case Cloudflare = 'cloudflare';
    case None = 'none';
}
