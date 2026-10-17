# フォーム

フォームの基底クラスは `MyVendor\MyProject\Form\ExtendedForm`（`source/app/src/Form/ExtendedForm.php`）です。
Aura.Input の `Fieldset`（1 つの入れ子）と `Collection`（同じ形の入れ子の繰り返し）を使った入れ子のフォームに対応しています。

標準の `Ray\WebFormModule\AbstractForm` は、トップレベルの項目しか検証せず、入れ子のエラーも取れません。
`ExtendedForm` は次の点を補います。

| 項目 | 内容 |
|---|---|
| 検証 | `apply()` が、子・孫の Fieldset と Collection の各行まで検証します。途中で止まらないので、エラーは全部集まります |
| エラー取得 | `error()` と `getFailureMessages()` が入れ子のエラーを返します。キーは HTML の name と同じパス表記です（例: `note`、`home[zip]`、`deliveries[1][zip]`） |
| 描画 | `widget()` と `input()` がパス表記を受け付けます |
| 不正な POST | 未知のキーは無視します。Fieldset / Collection に配列以外が来ても例外になりません |

## サンプル

| ページ | 内容 | 場所 |
|---|---|---|
| http://localhost/user/fieldset-demo | Fieldset（`home`）と Collection（`deliveries`） | `source/app/src/Form/Customer/UserFieldsetDemoForm.php` |

関連するファイルは次のとおりです。

| 役割 | ファイル |
|---|---|
| フォーム | `source/app/src/Form/Customer/UserFieldsetDemoForm.php` |
| Fieldset と Filter | `source/app/src/Form/Customer/Fieldset/AddressFieldset.php`、`AddressFilter.php` |
| Page | `source/app/src/Resource/Page/User/FieldsetDemo.php` |
| 入力クラス（`#[Input]`） | `source/app/src/InputQuery/Customer/FieldsetDemoInput.php` |
| テンプレート | `source/app/var/qiq/template/Page/User/FieldsetDemo.php` |

## 入れ子のフォームの作り方

### 1. Fieldset と Filter を作る

Fieldset は `ExtendedFieldset` を継承し、`init()` でフィールドを定義します。
検証は Aura.Input の `Filter` で書きます。トップレベルのフォームが使う Aura.Filter（`$this->filter->validate()`）とは別系統です。

```php
class AddressFieldset extends ExtendedFieldset
{
    public function init(): void
    {
        $this->setField('zip', 'text')->setAttribs(['required' => 'required']);
        $this->setField('city', 'text');
    }
}

class AddressFilter extends Filter
{
    public function __construct()
    {
        $this->setRule('zip', 'Zip is digit only.', static fn (mixed $zip) => ctype_digit($zip));
    }
}
```

### 2. フォームで Fieldset / Collection を宣言する

`init()` の先頭で `useFieldsets()` に、種別名 => Fieldset を返すファクトリの配列を渡します。
`new Builder([...])` を直接書かず、必ず `useFieldsets()` を使ってください。Collection の不正な POST への対策が、これで有効になります。

```php
class UserFieldsetDemoForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    public function init(): void
    {
        $this->useFieldsets([
            'address' => static fn () => new AddressFieldset(new Builder(), new AddressFilter()),
        ]);

        $this->setFieldset('home', 'address');        // 1 つ
        $this->setCollection('deliveries', 'address'); // 繰り返し
        $this->setField('note', 'textarea');
    }
}
```

### 3. Module にバインドする

通常のフォームと同じです。`HtmlModule` に `#[Named]` 用のバインドを追加し、`DefaultModule` では `toNull()` にします。

```php
// HtmlModule
$this->bind(FormInterface::class)->annotatedWith('user_fieldset_demo_form')->to(CustomerForm\UserFieldsetDemoForm::class);

// DefaultModule
$this->bind(FormInterface::class)->annotatedWith('user_fieldset_demo_form')->toNull();
```

### 4. Page で検証する

`#[FormValidation]` を `onPost` に付け、`onPostValidationFailed` を用意します。
Collection の行は POST されたキーから作られます。初期表示で行を出したいときは、`onGet` で行数分の空配列を `fill()` します。

```php
public function onGet(): static
{
    assert($this->form instanceof ExtendedForm);
    $this->form->fill(['deliveries' => [[], []]]); // 2 行ぶん表示

    return $this;
}

#[FormValidation]
public function onPost(#[Input] FieldsetDemoInput $input): static
{
    return $this;
}

public function onPostValidationFailed(#[Input] FieldsetDemoInput $input): static
{
    return $this;
}
```

### 5. Qiq テンプレートで表示する

子 Fieldset の項目は、Fieldset を取り出して `fieldset:` に渡します。
エラーは `userFieldsetError()` で、Fieldset から直接取ります（`ExtendedForm` は経由しません）。
トップレベルの項目のエラーは、これまでどおり `userFormError()` です。

```php
{{ $addressFieldset = $form->home }}
{{= userText(form: $form, input: 'zip', fieldset: $addressFieldset) }}
{{= userFieldsetError(fieldset: $addressFieldset, input: 'zip') }}

{{ foreach ($form->deliveries as $index => $addressFieldset): }}
    {{= userText(form: $form, input: 'zip', fieldset: $addressFieldset) }}
    {{= userFieldsetError(fieldset: $addressFieldset, input: 'zip') }}
{{ endforeach }}
```

## エラー取得の使い分け

| 場所 | 使うもの | エラーの取得元 |
|---|---|---|
| トップレベルの項目（`note` など） | `userFormError()` | `$form->error('note')` |
| 子 Fieldset の項目（`home` や `deliveries` の中） | `userFieldsetError()` | `$fieldset->error('zip')` |

エラーを取るのは Fieldset ですが、子 Fieldset の `filter()` を呼んでメッセージを作るのは `ExtendedForm::apply()` です。
標準の `AbstractForm::apply()` は、子の `filter()` を呼びません。

プログラムからパス表記で取ることもできます。

```php
$form->error('home[zip]');
$form->error('deliveries[1][zip]');
$form->widget('deliveries[0][zip]');
```

## 注意

- **`#[Input]` の型と合わせる**: Page の `#[Input]` で受ける入力クラス（例: `FieldsetDemoInput`）に合わない形の値は、フォームに届く前に 400（`ParameterException`）になります。必須の引数（例: `agree`）が足りないときも同じです。
- **孫の Fieldset**: Fieldset の中の Fieldset に配列以外が来ると、Aura.Input 標準の `Fieldset::fill()` で TypeError になります。守られているのは、フォーム直下と Collection の行の中までです。
- **Collection の件数制約**: 最小件数などの専用の仕組みはありません。必要なら、トップレベルのフィルタで件数を検証してください。
- **`submit()`**: `$_POST` を返します。ファイルアップロード（`$_FILES`）は含みません。
- **`ExtendedFieldset` のエラーのキャッシュ**: 最初に `error()` を呼んだときのメッセージを覚えます。検証の後に描画する通常の流れでは問題になりません。
