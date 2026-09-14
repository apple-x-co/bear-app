<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\ProviderInterface;

/**
 * ServerRequestInterface を提供
 *
 * @implements ProviderInterface<ServerRequestInterface>
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
final class ServerRequestProvider implements ProviderInterface
{
    /**
     * TODO: fromGlobals() は $_SERVER などの実スーパーグローバルを直接参照する。
     * 一方、bin/command.php から実行される CLI コマンド(例: send-email-from-email-queue)では、
     * 実際のリクエスト情報として使われるのは CliRouter が引数(argv)から組み立てた
     * 別の $server 配列であり、$_SERVER の中身とは一致しない。
     * つまり CLI コマンド実行中にこの Provider 経由で ServerRequestInterface を参照すると、
     * 実際に処理しているコマンドとは無関係な(空や不正確な)リクエスト情報を返す可能性がある。
     * 現状 CLI から参照されるのは Locale 判定のみで、CLI コマンドはテンプレート描画を
     * 行わないため実害はないが、CLI コマンドがリクエスト情報を参照する処理を
     * 追加する場合は要対応。
     */
    public function get(): ServerRequestInterface
    {
        return ServerRequest::fromGlobals();
    }
}
