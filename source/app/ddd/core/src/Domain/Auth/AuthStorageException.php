<?php

declare(strict_types=1);

namespace AppCore\Domain\Auth;

use AppCore\Exception\RuntimeException;

/**
 * 認証に使う保存先（アカウントや Remember me のトークン）を読み書きできなかった
 *
 * 認証の失敗（AuthenticationException）ではなく、システムの障害として扱う
 */
final class AuthStorageException extends RuntimeException
{
}
