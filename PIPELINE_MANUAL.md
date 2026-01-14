# ZIDOOKA Writing Pipeline Manual

このドキュメントは、ZIDOOKAブログへの記事投稿・更新フロー（CLIツール）の操作マニュアルです。

## 1. 環境セットアップ

### 前提条件
- Node.js がインストールされていること
- プロジェクトルートで依存関係がインストールされていること

```powershell
npm install
```

## 2. コマンド一覧

CLIツールは `src/index.js` を経由して実行します。

| コマンド | 説明 | 例 |
| --- | --- | --- |
| `auth` | WordPressとの認証接続を確認します | `node src/index.js auth` |
| `sync` | カテゴリ・タグ情報を同期し `data/metadata.json` を更新します | `node src/index.js sync` |
| `list` | ローカルのカテゴリ・タグ一覧を表示します | `node src/index.js list categories` |
| `post` | 指定したMarkdownファイルを投稿（または更新）します | `node src/index.js post drafts/article.md` |

---

## 3. 投稿ワークフロー

### Step 1: メタデータの同期（初回・変更時のみ）
WordPress側のカテゴリやタグが増えた場合は、ローカルの定義ファイルと同期します。

```powershell
node src/index.js sync
```
これにより `data/metadata.json` が更新されます。

**AIアシスタントへの指示:**
記事作成を依頼する際、AIは以下のコマンドで現在のカテゴリ一覧を確認し、適切なカテゴリ・タグを選択してください。
```powershell
node src/index.js list categories
# または
node src/index.js list tags
```

### Step 2: 記事ドラフトの作成
`drafts/` フォルダ内にMarkdownファイルを作成します。

**ファイル構成例:**
```markdown
---
title: "記事のタイトル"
date: 2025-02-10 20:00:00
categories: 
  - WordPress
tags: 
  - WordPress
  - トラブルシューティング
status: publish
slug: my-article-slug
featured_image: ../images/thumbnail.png # または thumbnail: ...
---

ここに本文を書きます。
画像は `![alt](../images/pic.png)` のように相対パスで指定可能です。
```

**カテゴリ・タグのルール:**
- **カテゴリ:** 新規作成は**禁止**です。必ず `node src/index.js list categories` で確認できる既存のカテゴリを使用してください。存在しないカテゴリを指定するとエラーになります。
- **タグ:** 自由に新規作成可能です。存在しないタグを指定した場合、自動的にWordPress上に作成されます。

**Frontmatter（ヘッダー設定）の仕様:**
- `title`: 記事タイトル
- `date`: 公開日時（未来の日付なら予約投稿になります）
- `categories`: カテゴリ名（配列）
- `tags`: タグ名（配列）
- `status`: `publish`（公開/予約）, `draft`（下書き）, `private`（非公開）
- `slug`: URLスラッグ（**重要**: これが既存記事と一致すると「更新」扱いになります）
- `featured_image` (または `thumbnail`): アイキャッチ画像のローカルパス

### Step 3: 記事の投稿・更新
作成したファイルを指定してコマンドを実行します。

```powershell
node src/index.js post drafts/my-article.md
```

**処理の流れ:**
1. 記事内の画像を自動でWordPressにアップロードし、URLを置換
2. アイキャッチ画像をアップロードして設定
3. `slug` を元に既存記事を検索
   - **既存あり**: 記事を更新（Update）
   - **既存なし**: 新規記事を作成（Create）
4. 完了後、記事のURLが表示されます

---

## 4. 記事の書き方（Gutenberg対応）

本システムはMarkdownをWordPressのGutenbergブロック形式に自動変換します。
以下の専用記法を使用することで、リッチな装飾ブロックを生成できます。

### インライン装飾
| 記法 | 出力クラス | 用途 |
| --- | --- | --- |
| `==テキスト==` | `zdk_i_strong` | 強調・ハイライト |
| `` `テキスト` `` | `zdk_i_code` | インラインコード |

### ブロック装飾
以下の記法で、特定のクラスを持つグループブロックを作成できます。

```markdown
:::note
ここにメモの内容を書きます。
Markdownも使えます。
:::
```

**対応ブロック一覧:**
| 記法 | 出力クラス | 用途 |
| --- | --- | --- |
| `:::note` | `zdk_b_note` | 補足・メモ |
| `:::warning` | `zdk_b_warning` | 注意・警告 |
| `:::step` | `zdk_b_step` | 手順・ステップ |
| `:::example` | `zdk_b_example` | 具体例 |
| `:::conclusion` | `zdk_b_conclusion` | 結論・まとめ |

**注意点:**
- 生のHTMLタグは使用しないでください（自動的にエスケープされます）。
- ブロックのネスト（`:::`の中に`:::`）は現在サポートしていません。

## 5. ディレクトリ構造

```
zidooka-writing/
├── data/
│   └── metadata.json      # カテゴリ・タグのIDマッピングデータ（syncで更新）
├── drafts/                # 記事のMarkdownファイル置き場
├── images/                # 記事で使用する画像リソース
├── src/
│   ├── index.js           # CLIエントリポイント
│   ├── config/            # 設定ファイル
│   └── services/          # ロジック（投稿、画像処理、Markdown変換）
└── package.json
```

## 6. トラブルシューティング

- **認証エラー**: `.env` ファイル（または環境変数）の `WP_USERNAME`, `WP_PASSWORD` が正しいか確認してください。
- **カテゴリが見つからない**: `node src/index.js sync` を実行して最新のカテゴリ情報を取得してください。
- **画像が出ない**: パスが間違っていないか確認してください。Markdownファイルからの相対パスで記述する必要があります。

## 7. データエクスポート

WordPress上の全記事データを取得し、ローカルのJSONファイルとして保存します。
過去記事の分析や、AIによる傾向分析に使用します。

```powershell
node src/export_all_posts.js
```

- 出力先: `data/all_posts.json`
- 含まれる情報: タイトル、日付、更新日、カテゴリ、スラグ、タグ


## 8. スクリーンショット撮影

推奨の方法として、リポジトリに含まれる Playwright スクリプトを使用します。

### 1. 単発スクリーンショット (PC Viewport)
指定したURLのスクリーンショット（ファーストビュー）を撮影します。記事のアイキャッチや説明画像に最適です。

```powershell
# 使用法: node scripts/agent-browser-screenshot.mjs <URL> <保存パス>
node scripts/agent-browser-screenshot.mjs https://vercel.com/ images-agent-browser/vercel.png
```

- デフォルト値:
    - URL: `https://www.zidooka.com/`
    - 出力: `images-agent-browser/zidooka-pc-viewport.png`
    - サイズ: 1920x900 (PC基準)
    - ※環境変数 `SCREEN_WIDTH`, `SCREEN_HEIGHT` でサイズ変更可能

### 2. マルチデバイスギャラリー撮影
トップページの PC(Full)、SP、Tablet 版をまとめて撮影します。

```powershell
node scripts/agent-browser-gallery.mjs
```

### 3. (参考) agent-browser CLI
`npx agent-browser` が動作環境にある場合は、対話的に撮影することも可能です（Windows環境等で動作しない場合は上記スクリプトを使用してください）。

---

上記いずれかの手順でスクショを取得してください.
