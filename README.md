# ZIDOOKA Writing (Agent CLI)

まず読むもの（Must-Read）
- `PIPELINE_MANUAL.md`（全体ワークフロー）
- `docs/snippets/emphasis.md`（強調表現ルール）

## 概要
CLI 経由で記事の作成・校正・画像処理・WordPress 投稿を行うためのワークスペースです。AI アシスタントが指示に従って Node.js スクリプトを実行します。

## ワークフロー（概要）
1. メタデータ同期: `node src/index.js sync`（カテゴリ/タグのローカル更新）
2. 下書き作成: `drafts/` に Markdown を作成（Frontmatter ルールは `PIPELINE_MANUAL.md`）
3. 投稿/更新: `node src/index.js post drafts/xxx.md`

## ディレクトリ構成
```
zidooka-writing/
  ├── data/
  │   └── metadata.json
  ├── drafts/
  ├── images/
  ├── scripts/
  │   └── remote-agent/   # リモート編集用ユーティリティ
  ├── src/
  │   ├── index.js
  │   ├── writer.js
  │   ├── vision.js
  │   ├── wp.js
  │   └── utils.js
  ├── docs/
  │   └── REMOTE_UPLOAD.md
  ├── .env
  └── package.json
```

## コマンド（主要）
- カテゴリ/タグ同期: `node src/index.js sync`
- 投稿: `node src/index.js post drafts/article.md`

## リモートアップロード（テーマファイル）
リモートの WordPress テーマに対し、安全にファイルをアップロードするためのスクリプトを同梱（バックアップ自動作成・許可パス制限あり）。

前提
- Node.js 18+
- 依存のインストール: `npm install`
- `.env` に接続情報と許可パスを設定（例は同梱）
  - `REMOTE_PROTOCOL=WEBDAV`（または `SFTP` / `FTPS`）
  - `WEBDAV_URL`, `WEBDAV_USER`, `WEBDAV_PASS`（またはプロトコル別の認証情報）
  - `REMOTE_BASES="zidooka/wp-content/themes/picostrap5/,zidooka/wp-content/themes/picostrap5-child/,zidooka/wp-content/themes/picostrap5-child-base/"`

接続チェック
- 許可パス全体: `node scripts/remote-agent/index.js check`
- ディレクトリ一覧: `node scripts/remote-agent/index.js ls --dir="zidooka/wp-content/themes/picostrap5-child-base/"`

アップロード（リモートに `<file>.bak.<timestamp>` を自動作成）
- `single.php` を更新:
  - `node scripts/remote-agent/index.js push --file="zidooka/wp-content/themes/picostrap5-child-base/single.php" --src="C:\\Users\\user\\Documents\\zidooka_writing\\downloads\\picostrap5-child-base\\single.php"`
- `functions.php` を更新:
  - `node scripts/remote-agent/index.js push --file="zidooka/wp-content/themes/picostrap5-child-base/functions.php" --src="C:\\Users\\user\\Documents\\zidooka_writing\\downloads\\picostrap5-child-base\\functions.php"`

補足
- `npm run remote:agent -- <cmd>` でも実行できます。
- ロールバックは作成された `.bak.<timestamp>` を元ファイル名へ戻すか、ホストのファイルマネージャで復元してください。

