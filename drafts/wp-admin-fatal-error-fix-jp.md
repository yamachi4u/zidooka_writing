---
title: "WordPress管理画面だけ「重大なエラー」で入れない！wp-config.phpから原因特定して直した話"
slug: "wp-admin-fatal-error-fix-jp"
status: "publish"
categories: 
  - "WordPress"
tags: 
  - "WordPress"
  - "Troubleshooting"
  - "wp-config.php"
  - "Fatal Error"
featured_image: "../images/image copy 15.png"
---

# WordPress管理画面だけ「重大なエラー」で入れない！wp-config.phpから原因特定して直した話

![サムネイル](../images/image%20copy%2015.png)

ある日突然、WordPressの管理画面に入ろうとしたら**「このWebサイトで重大なエラーが発生しました」**という絶望的なメッセージが表示されました。

しかし、不思議なことに**Webサイトの表側（フロントエンド）は普通に表示されている**のです。管理画面だけが死んでいる状態。

今回は、この状況から `wp-config.php` を使って原因を特定し、復旧させた手順をメモしておきます。

## 状況：管理画面だけ真っ白（またはエラーメッセージ）

*   **症状**: `/wp-admin/` にアクセスすると「重大なエラー」画面になる。
*   **Webサイト**: 普通に見れる。記事も読める。
*   **心当たり**: `functions.php` をいじった記憶があるようなないような...。

## 手順1：wp-config.php でデバッグモードをONにする

まずはエラーの正体を知る必要があります。FTPやサーバーのファイルマネージャーで `wp-config.php` を開き、デバッグ設定を確認しました。

元々の記述はこうなっていました。

```php
define( 'WP_DEBUG', false );
```

これではエラーが表示されません。そこで、以下のように書き換えました。

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
```

**注意点**: 今回確認したところ、`wp-config.php` の末尾（`require_once ABSPATH . 'wp-settings.php';` の後）にも `define('WP_DEBUG', true);` が追記されているという**二重定義**の状態になっていました。これは設定が反映されなかったり、予期せぬ挙動の原因になるので、正しい位置（`wp-settings.php` の読み込み前）に1つだけ記述するように修正しました。

## 手順2：エラーメッセージを確認する

デバッグモードをONにして再度管理画面にアクセスすると、具体的なエラーメッセージが表示されました。

```
Fatal error: Uncaught Error: Call to a member function get() on false in .../themes/parent-theme/inc/options-page.php:18
Stack trace:
#0 .../themes/child-theme/functions.php(61): get_parent_theme_version_check()
#1 ...
#4 .../wp-admin/admin-header.php(313): do_action('admin_notices')
...
```

犯人はこれです。

*   **場所**: 子テーマの `functions.php` 61行目
*   **関数**: 親テーマのバージョンチェック関数
*   **フック**: `admin_notices`（管理画面のお知らせ表示）

親テーマのバージョンチェックをする関数が、管理画面の特定のタイミングで呼び出された際に、内部でオブジェクトが正しく取得できず（`false`）、そこで `get()` メソッドを呼ぼうとして死んでいたようです。

フロントエンドではこの `admin_notices` フックが走らないため、サイト自体は無事だったというわけです。

## 手順3：functions.php を修正して復旧

原因がわかれば修正は簡単です。子テーマの `functions.php` を開き、該当する `admin_notices` の処理をコメントアウト（または削除）しました。

```php
// 修正前：これがエラーの原因
/*
add_action( 'admin_notices', function  () {
    if( (get_parent_theme_version_check())>=3.0) return; 
    $message = __( 'This Child Theme requires at least Version 3.0.0...', 'textdomain' );
    printf( '<div class="%1$s"><h1>%2$s</h1></div>', esc_attr( 'notice notice-error' ), esc_html( $message ) );
} );
*/
```

このコードを無効化したところ、無事に管理画面にログインできるようになりました！

## まとめ

*   **管理画面だけエラー**の場合は、管理画面専用のフック（`admin_notices` など）や、管理画面でのみ実行される処理が怪しい。
*   **wp-config.php** の `WP_DEBUG` を `true` にして、まずはエラー文言を見るのが最短ルート。
*   `wp-config.php` の記述位置（`wp-settings.php` より前）や重複定義にも注意。

同じような現象に遭遇した方の参考になれば幸いです。
