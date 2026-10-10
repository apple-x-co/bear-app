# Claude Code 開発ガイド

このドキュメントは、Claude Code を使用して bear-app プロジェクトを効率的に開発するためのガイドです。

## プロジェクト概要

bear-app は BEAR.Sunday フレームワークをベースにした DDD + CQRS アーキテクチャの Web アプリケーションです。

### 技術スタック

- **PHP**: 8.3+
- **フレームワーク**: BEAR.Sunday
- **アーキテクチャ**: DDD（Domain-Driven Design）+ CQRS
- **データベース**: MySQL 8.0+
- **テンプレート**: Qiq
- **CSS**: Tailwind CSS
- **AOP**: Ray.Aop
- **DI**: Ray.Di

## アーキテクチャのポイント

### レイヤー分離

このプロジェクトは4層のレイヤーアーキテクチャを採用しています：

1. **Domain 層** (`source/app/ddd/core/src/Domain/`)
   - ビジネスロジックの中核
   - Entity、Value Object、Domain Service
   - フレームワーク非依存

2. **Application 層** (`source/app/ddd/core/src/Application/`)
   - ユースケースの実装
   - InputData / OutputData による入出力定義
   - Domain 層を組み合わせた処理フロー

3. **Infrastructure 層** (`source/app/ddd/core/src/Infrastructure/`)
   - 技術的実装の詳細
   - Repository 実装、Query/Command（CQRS）
   - データベース Entity

4. **Presentation 層** (`source/app/src/`)
   - BEAR.Sunday Resource Object
   - Form、Interceptor、Template

### CQRS パターン

- **Query（読み取り）**: Ray.MediaQuery + SQL ファイル
  - 場所: `source/app/ddd/core/src/Infrastructure/Query/`
  - SQL: `source/app/var/sql/`

- **Command（書き込み）**: Repository パターン
  - 場所: `source/app/ddd/core/src/Infrastructure/Persistence/`

### AOP（アスペクト指向プログラミング）

アトリビュートベース（一部アノテーション）のインターセプターで横断的関心事を分離：

- `#[AdminGuard]`: 管理者認証チェック
- `#[AdminPasswordProtect]`: パスワード保護
- `#[RequiredPermission]`: 権限チェック
- `#[RateLimiter]`: レート制限
- `@FormValidation`: フォーム検証
- `#[Cacheable]` / `#[NoHttpCache]` など: キャッシュ制御（詳細は「8. キャッシュの設定」）

## 開発時の注意点

### 1. レイヤーの依存関係を守る

```
Presentation → Application → Domain
                      ↓
               Infrastructure
```

- Domain 層は他の層に依存しない
- Application 層は Domain 層のみに依存
- Infrastructure 層は Domain・Application 層のインターフェースを実装
- Presentation 層はすべての層を利用可能

### 2. CQRS の使い分け

**Query（読み取り）を使うべき場合**:
- データ取得のみ
- 複雑な JOIN が必要
- パフォーマンスが重要

**Command（書き込み）を使うべき場合**:
- データ更新・削除
- ビジネスロジックが必要
- トランザクション管理が必要

### 3. アトリビュートの順序

Resource メソッドでアトリビュートを使う場合、以下の順序を推奨：

```php
#[AdminGuard]                          // 1. 認証チェック
#[AdminPasswordProtect]                // 2. パスワード保護
#[RequiredPermission('resource', Permission::Read)]  // 3. 権限チェック
#[RateLimiter(10, 60)]                // 4. レート制限
public function onGet(): static
```

### 4. ファイル配置ルール

#### 新しい Domain を追加する場合

```
source/app/ddd/core/src/Domain/YourDomain/
├── YourDomainException.php           # ドメイン例外
├── YourDomain.php                    # ドメインオブジェクト
└── YourValueObject.php               # 値オブジェクト（必要に応じて）
```

#### 新しい UseCase を追加する場合

```
source/app/ddd/core/src/Application/YourUseCase/
├── YourUseCase.php                   # ユースケース実装
├── YourUseCaseInputData.php          # 入力データ
└── YourUseCaseOutputData.php         # 出力データ
```

#### 新しい Page Resource を追加する場合

```
source/app/src/Resource/Page/Admin/YourPage.php
source/app/var/qiq/template/Page/Admin/YourPage.php
```

### 5. データベース操作

#### Query の追加

1. Interface を定義: `source/app/ddd/core/src/Infrastructure/Query/YourQueryInterface.php`
2. SQL を作成: `source/app/var/sql/your_query.sql`

#### Command の追加

1. Interface を定義: `source/app/ddd/core/src/Infrastructure/Query/YourCommandInterface.php`
2. SQL を作成: `source/app/var/sql/your_command.sql`

#### Repository の追加

1. Repository Interface を Domain 層に定義
2. Repository 実装を Infrastructure 層に作成
3. Module でバインディング

```php
$this->bind(YourRepositoryInterface::class)->to(YourRepository::class);
```

### 6. フォームの作成

```php
// 1. Form クラスを作成
// source/app/src/Form/Admin/YourForm.php
class YourForm extends ExtendedForm
{
    public function init(): void
    {
        $this->setField('field_name', 'text')
            ->setAttribs([
                'id' => 'field_name',
                'class' => 'form-input',
            ]);

        $this->filter()
            ->sanitize('field_name')->to('string')
            ->validate('field_name')->is('strlenMin', 1);
    }
}

// 2. Resource で使用
/** @FormValidation */
public function onPost(): static
{
    // バリデーション済みの値を取得
    $values = $this->form->getValues();
}
```

### 7. メールテンプレートの追加

```
source/app/var/email/
├── subject/your_mail.txt    # 件名
├── text/your_mail.txt       # テキスト版本文
└── html/your_mail.php       # HTML版本文
```

使用方法:

```php
$this->transport->send($email);
```

### 8. キャッシュの設定

BEAR.Sunday のキャッシュ機構（`bear/query-repository`）を使います。`PackageModule` が `QueryRepositoryModule` を自動でインストールするので、リソースにアトリビュートを付けるだけで有効になります（Module の追加は不要）。

#### 使い分け

| 用途 | アトリビュート | 備考 |
|---|---|---|
| ユーザー非依存の GET を一定時間キャッシュ | `#[Cacheable(expirySecond: N)]` | サーバー側キャッシュ ＋ ETag ＋ `Cache-Control: max-age` を自動付与。`$headers` に `Cache-Control` を手書きしない |
| 指定した日時までキャッシュ | `#[Cacheable(expiryAt: 'key')]` | body の `key` に `strtotime()` で解釈できる日時を入れる。キーが無いと `ExpireAtKeyNotExists` |
| ヘッダーだけ付ける（サーバー側キャッシュなし） | `#[HttpCache(maxAge: N, sMaxAge: N)]` | |
| キャッシュさせない | `#[NoHttpCache]` | `Cache-Control: private, no-store, no-cache, must-revalidate` |
| 書き込みで別リソースのキャッシュを破棄 | `#[Purge(uri: '...?id={id}')]` | 次の GET で作り直される |
| 書き込みで別リソースのキャッシュを再生成 | `#[Refresh(uri: '...?id={id}')]` | その場で GET し直して保存する |
| 全体キャッシュ（TTL なし・イベント駆動） | `#[CacheableResponse]` | 書き込み（`onPut` など）で更新。埋め込み（`#[Embed]`）の変更にも追従する |
| 部分キャッシュ（ドーナツ） | `#[DonutCache]` ＋ `#[RefreshCache]` | 全体は ETag なし。キャッシュされるのはドーナツ部分だけ |

#### ルール

* **ユーザー固有の状態に依存する Page は `#[NoHttpCache]` を付ける。** `Page/Admin`・`Page/User` 配下の `onGet` を持つクラスが対象です（`AdminContext`・フォームの CSRF トークン・セッション値に依存するため）。`#[Cacheable]` を付けると他のユーザーの情報が漏れます
* `#[HttpCache]` / `#[NoHttpCache]` は**クラス自身に付けたものだけ**が効きます。`BaseAdminPage` などの親クラスに付けても子クラスには効きません
* `#[HttpCache]` / `#[NoHttpCache]` が `Cache-Control` を付けるのは**レスポンスコードが 200 のときだけ**です（303 リダイレクトなどは対象外）
* `#[Purge]` / `#[Refresh]` は **`#[Cacheable]` ではないクラス**の書き込みメソッドに付けます（`#[Cacheable]` のクラスは書き込みメソッドが自動で自分のキャッシュを更新します）
* `#[DonutCache]` / `#[CacheableResponse]` で `#[Embed]` した穴は、`DonutRequest` として文字列化されてテンプレートに差し込まれます。**JSON（App リソース）では展開されない**ので、Qiq テンプレートを使う Page リソースで使い、テンプレートでは `{{= $穴 }}` とエスケープなしで出力します

#### サンプル

動作確認用のサンプルを `CacheDemo/` に置いています。各クラスの doc コメントに確認手順を書いています。

| ファイル | 内容 |
|---|---|
| `source/app/src/Resource/App/CacheDemo/Http.php` | `#[HttpCache]` |
| `source/app/src/Resource/App/CacheDemo/ExpiryAt.php` | `#[Cacheable(expiryAt: ...)]` |
| `source/app/src/Resource/App/CacheDemo/Quote.php`、`QuoteCommand.php` | `#[Purge]` / `#[Refresh]`（`Quote` が無効化される側） |
| `source/app/src/Resource/Page/CacheDemo/Donut.php`、`Clock.php` | `#[DonutCache]` ＋ `#[RefreshCache]` ＋ `#[Embed]` |
| `source/app/src/Resource/Page/CacheDemo/Article.php`、`Comment.php` | `#[CacheableResponse]` ＋ `#[Embed]`（依存関係による無効化） |

実際のキャッシュ設定の例は `Resource/App/Index.php`（`#[Cacheable]`）と `Resource/Page/Admin/Login.php`（`#[NoHttpCache]`）を参照してください。

#### 動作確認の注意

* **dev コンテキストではサーバー側のキャッシュは効きません**（`ResourceObjectPool` が `NullAdapter` のため）。`ETag` や `Cache-Control` のヘッダーは確認できますが、キャッシュヒットや無効化の確認は `prod-` コンテキスト（`prod-hal-api-app`、`prod-html-app` など）で行います
* prod コンテキストは DI をコンパイルして `var/build/<context>/` に保存します。リソースを追加・変更したあとに `Unbound` エラーが出たら、`var/build/<context>/` と `var/tmp/<context>/` を削除して作り直させてください
* ホストの PHP が 8.3 未満の場合は、`bear-web` コンテナ内（`/var/www/html`）で実行します
* `ProdModule` の `CacheVersionModule((string) time())` により、デプロイのたびにキャッシュが無効になります。Web サーバーを複数台にする場合は、`StorageRedisDsnModule`（または `StorageMemcachedModule`）でキャッシュを共有します（現在は `// TODO`、`docker/compose.yml` に Redis・Memcached はありません）

## よく使うコマンド

### 開発サーバー起動

```bash
cd docker
./docker-startup.sh
```

### コマンド実行

```bash
# メール送信キューの処理
php ./bin/command.php post /send-email-from-email-queue

# 危険なパスワードのインポート
php ./bin/command.php post /import-bad-passwords

# 削除済み管理者の完全削除
php ./bin/command.php post /delete-admins
```

### フロントエンドビルド

```bash
cd source/app/public/admin-src

npm run build
npm run tailwindcss
```

### テスト実行

```bash
cd source/app
composer test
```

### 静的解析

```bash
cd source/app
composer cs
composer sa
```

## Claude Code での作業フロー

### 1. 新機能の実装

```
1. Domain 層の設計
   - ドメインサービスインターフェースの定義
   - エンティティ・値オブジェクトの作成

2. Application 層の実装
   - UseCase の作成
   - InputData / OutputData の定義

3. Infrastructure 層の実装
   - Repository 実装
   - Query/Command の作成
   - SQL ファイルの作成

4. Presentation 層の実装
   - Resource Object の作成
   - Form の作成
   - Template の作成

5. テストの作成
   - Unit Test
   - Integration Test
```

### 2. バグ修正

```
1. 問題箇所の特定
   - ログの確認
   - コードの調査

2. テストケースの作成（既存のバグの場合）

3. 修正の実装

4. テストの実行

5. 関連箇所への影響確認
```

### 3. リファクタリング

```
1. 現状の理解
   - 既存コードの調査
   - 依存関係の確認

2. 改善計画の策定

3. テストの作成（カバレッジ不足の場合）

4. リファクタリングの実行

5. テストの実行

6. パフォーマンスの確認
```

## コーディングガイドライン

### PSR-12 準拠

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Resource\Page\BaseAdminPage;

class YourPage extends BaseAdminPage
{
    #[AdminGuard]
    public function onGet(): static
    {
        // Implementation

        return $this;
    }
}
```

### 型宣言の徹底

```php
// Good
public function getUserById(int $id): User|null
{
    // ...
}

// Bad
public function getUserById($id)
{
    // ...
}
```

### 例外処理

```php
// Domain 例外を使用
if ($password === '') {
    throw new InvalidPasswordException('Password is required');
}

// Infrastructure 例外はキャッチして Domain 例外に変換
try {
    $this->db->execute($query);
} catch (PDOException $e) {
    throw new DatabaseException('Failed to execute query', 0, $e);
}
```

### 命名規則

- **クラス名**: PascalCase（例: `AdminAuthGuardian`）
- **メソッド名**: camelCase（例: `onGet`, `getUserById`）
- **定数**: UPPER_SNAKE_CASE（例: `MAX_ATTEMPTS`）
- **プライベートプロパティ**: camelCase（例: `$userId`）

## トラブルシューティング

### 1. "Class not found" エラー

```bash
# Composer オートロードの再生成
cd source/app
composer dump-autoload
```

### 2. データベース接続エラー

```bash
# .env.json の確認
cat .env.json

# データベース接続テスト
mysql -u your_user -p your_database
```

### 3. テンプレートが表示されない

```bash
# キャッシュのクリア
rm -rf source/app/var/tmp/*
```

### 4. フロントエンドが反映されない

```bash
# 再ビルド
cd source/app/public/admin-src
npm run build
```

### 5. 権限エラー

```php
// admin_permissions テーブルを確認
SELECT * FROM admin_permissions WHERE admin_id = ?;

// 権限を追加（全権限）
INSERT INTO admin_permissions (admin_id, access, resource_name, permission_name)
VALUES (1, 'allow', '*', 'privilege');
```

### 6. キャッシュが効かない

- dev コンテキストではサーバー側のキャッシュは効きません。`prod-` コンテキストで確認してください
- リソースを追加したあとに `Unbound` エラーが出る場合は、DI のコンパイル結果が古い状態です

```bash
rm -rf var/build/<context> var/tmp/<context>
```

- `#[HttpCache]` / `#[NoHttpCache]` が効かない場合は、親クラスではなくそのクラス自身に付いているか、レスポンスコードが 200 かを確認してください

## セキュリティチェックリスト

新機能を実装する際は、以下を確認してください：

- [ ] 認証が必要なページに `#[AdminGuard]` / `#[UserGuard]` が付いているか
- [ ] 重要な操作に `#[AdminPasswordProtect]` が付いているか
- [ ] 権限チェックが必要な場合 `#[RequiredPermission]` が付いているか
- [ ] レート制限が必要な場合 `#[RateLimiter]` が付いているか
- [ ] ユーザー入力をそのまま SQL に使用していないか
- [ ] パスワードがハッシュ化されているか
- [ ] 機密情報がログに出力されていないか
- [ ] XSS 対策（テンプレートでのエスケープ）が行われているか
- [ ] CSRF 対策（フォームトークン）が実装されているか

## パフォーマンスチェックリスト

- [ ] N+1 問題が発生していないか
- [ ] 不要な JOIN が含まれていないか
- [ ] インデックスが適切に設定されているか
- [ ] キャッシュが活用できるか（ユーザー非依存の GET は `#[Cacheable]`、認証・フォーム・セッションに依存する Page は `#[NoHttpCache]`）
- [ ] 不要なデータを取得していないか
- [ ] トランザクションが適切に使用されているか

## 参考リンク

### BEAR.Sunday
- [公式ドキュメント](https://bearsunday.github.io/manuals/1.0/ja/)
- [GitHub](https://github.com/bearsunday/BEAR.Sunday)

### Ray.Di / Ray.Aop
- [Ray.Di](https://github.com/ray-di/Ray.Di)
- [Ray.Aop](https://github.com/ray-di/Ray.Aop)

### Qiq Template Engine
- [公式ドキュメント](https://qiqphp.com/)
- [GitHub](https://github.com/qiqphp/qiq)

### Aura Framework
- [Aura.Input](https://github.com/auraphp/Aura.Input)
- [Aura.Filter](https://github.com/auraphp/Aura.Filter)
- [Aura.Session](https://github.com/auraphp/Aura.Session)

### その他
- [PHPStan](https://phpstan.org/)
- [Tailwind CSS](https://tailwindcss.com/)

---

このガイドは、Claude Code での開発を効率化するために作成されました。不明点があれば、このドキュメントを参照しながら開発を進めてください。
