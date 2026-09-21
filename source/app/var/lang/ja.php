<?php

declare(strict_types=1);

// 翻訳キー ... {コンテキスト}.{画面/機能}.{項目}
// サフィックスは同一対象に複数の文言が必要な場合のみ付与する
// 例: .email と .email_placeholder が共存する場合に .email_placeholder を追加

return [
    // ◆◆◆ admin ◆◆◆
    'admin.email.created' => 'Eメールアドレス宛てにメールを送信しました',
    'admin.email.verified' => 'Eメールアドレスを確認しました',
    'admin.email.deleted' => 'Eメールアドレスを削除しました',
    'admin.password.updated' => 'パスワードを更新しました',
    'admin.reset_password.decrypt_error' => 'パスワードリセット時にエラーが発生しました',
    'admin.sign_up.decrypt_error' => 'アカウント作成時にエラーが発生しました',
    // ◆◆◆ common ◆◆◆
    'common_app_name' => 'bear-app (ja)',
    // ◆◆◆ email ◆◆◆
    // ◆◆◆ public ◆◆◆
    'public.login.error' => '認証エラー',
    'public.login.invalid_password' => '有効なパスワードを入力してください',
    'public.login.invalid_username' => '有効なユーザー名を入力してください',
    'public.login.password' => 'パスワード',
    'public.login.submit' => 'ログイン',
    'public.login.username' => 'ユーザー名',
];
