# 認証エラーの解決方法 (401 Unauthorized)

「現在ログインしていません」というエラーが出る場合、サーバー側で「Authorizationヘッダー」が削除されている可能性が高いです（Xserverなどのレンタルサーバーでよく発生します）。

以下の手順で、WordPressサーバー上の `.htaccess` ファイルを編集してください。

## 手順

1. FTPソフト（FileZillaなど）またはサーバーのファイルマネージャーで、WordPressのインストールディレクトリを開く。
2. `.htaccess` というファイルを探してダウンロード（または編集）する。
3. ファイルの先頭（`# BEGIN WordPress` の直前あたり）に、以下のコードを追加する。

```apache
# REST API Authorization Header Fix
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule ^(.*) - [E=HTTP_AUTHORIZATION:%1]
</IfModule>
```

4. 保存してアップロードする。

## その他の原因

- **ユーザー名の間違い**: `WP_USER` は「表示名」ではなく「ログインID」です。
- **セキュリティプラグイン**: `SiteGuard WP Plugin` などの「REST API無効化」設定がONになっていないか確認してください。
