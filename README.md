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
  ├── data/                  # メタデータ・投稿データ
  │   └── metadata.json
  ├── docs/                  # 技術ドキュメント・スタイルガイド
  │   ├── snippets/
  │   ├── troubleshooting/
  │   ├── REMOTE_UPLOAD.md
  │   ├── ZIDOOKA_STYLE.md
  │   └── ...
  ├── downloads/             # WPテーマファイル・テンプレート
  ├── drafts/                # 記事の下書き
  ├── drat/                  # 一時メモ・インシデントログ
  ├── images/                # 記事用画像・サムネイル
  ├── images-agent-browser/  # スクリーンショット出力先
  ├── scripts/               # スクリプト
  │   ├── remote-agent/      #   リモート編集用ユーティリティ
  │   └── generate-thumbnail.cjs
  ├── src/                   # CLIツール本体
  │   ├── index.js
  │   ├── cli/
  │   └── services/
  ├── .env
  └── package.json
```

## コマンド（主要）
- カテゴリ/タグ同期: `node src/index.js sync`
- 投稿: `node src/index.js post drafts/article.md`
- GA4 レポート: `npm run ga4 -- --preset overview`
- GSC クエリ: `npm run gsc -- --site=https://www.zidooka.com/ --preset top-queries --start-date=2026-02-25 --end-date=2026-03-23`

## GA4 / GSC 連携
サービスアカウント JSON を用いた read-only アクセスに対応しています。

`.env` 例:
```powershell
GOOGLE_SERVICE_ACCOUNT_KEY_PATH=C:\Users\user\.secrets\google\codex-ga-gsc.json
GOOGLE_GA4_PROPERTY_ID=344037190
GOOGLE_GSC_SITE=https://www.zidooka.com/
```

例:
- `npm run ga4 -- --preset overview`
- `npm run ga4 -- --preset landing-pages --limit 20`
- `npm run gsc -- --site=https://www.zidooka.com/ --preset top-queries --start-date=2026-02-25 --end-date=2026-03-23`
- `npm run gsc -- --site=https://tools.zidooka.com/ --preset top-pages --start-date=2026-02-25 --end-date=2026-03-23`
- `npm run seo:followup`
  - 直近7日 vs 前7日で、SEO改善対象ページの GA4 / GSC 差分をまとめた Markdown / JSON レポートを `daily/seo-followup/` に出力
  - このリポジトリでは ZIDOOKA の GA4 プロパティと GSC サイトを既定値として持つため、通常は `--key-file` だけ渡せばよい

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
