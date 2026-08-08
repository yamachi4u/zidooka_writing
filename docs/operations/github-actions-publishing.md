# GitHub Actions Publishing Workflow (設計)

> Issue #3: Design GitHub Actions publishing workflow for ChatGPT/Codex

## 1. ゴール

ChatGPT / Codex などの外部エージェントが、ローカルの `.env` を共有せずに GitHub 経由で記事を準備し、公開は安全な CI フローで行えるようにする。

- 記事準備（draft 作成 / PR レビュー）は GitHub 上で完結
- 公開（WordPress REST 投稿）は GitHub Secrets のみで動作
- 手動 `workflow_dispatch` とマージトリガーの両方をサポート

## 2. アーキテクチャ

```text
外部エージェント (ChatGPT/Codex)
        │  draft 作成 / images 追加
        ▼
   PR (branch: feature/xxx)
        │  pull_request review
        ▼
   main へマージ
        │
        ▼
.github/workflows/publish-article.yml
        │  workflow_dispatch (PR 番号指定) が基本
        ▼
   validate → thumbnail → post (WordPress REST) → IndexNow ping
        ▼
   results を PR コメントに返す
```

### 設計判断

| 判断 | 理由 |
|---|---|
| 自動公開はマージ時にしない | 誤公開防止。`workflow_dispatch` で明示的に公開する |
| Secrets は `WP_*` のみ | ローカル `.env` を GitHub に上げない |
| `post-pair` を基本 | 日本語＋英語ペア公開がデフォルト |
| 公開結果を PR コメント | 外部エージェントが後工程を続けやすい |

## 3. 必要な Secrets

| Secret | 用途 | 由来 |
|---|---|---|
| `WP_API_URL` | WordPress REST API ベース URL | `.env` の `WP_API_URL` |
| `WP_MEDIA_API_URL` | メディア API URL（任意。未指定なら API_URL） | `.env` の `WP_MEDIA_API_URL` |
| `WP_USER` | WordPress ユーザー名 | `.env` の `WP_USER` |
| `WP_APP_PASSWORD` | Application Password | `.env` の `WP_APP_PASSWORD` |
| `WP_TIMEZONE` | タイムゾーン（任意。既定 Asia/Tokyo） | `.env` の `WP_TIMEZONE` |
| `INDEXNOW_KEY` | IndexNow キー（任意。公開後の ping） | ローカルの IndexNow 設定 |

`.env` は `.gitignore` に含まれ、GitHub には一切コミットしない。

## 4. Workflow ファイル

- `.github/workflows/publish-article.yml` — 実装済み（下記参照）

### トリガー

- `workflow_dispatch`（推奨）: `draft path` と `PR number` を指定して公開
- `pull_request` は validation のみ（dry-run）を実行して結果をコメント

### ステップ

1. checkout + Node 22 setup + `npm ci --ignore-scripts`
2. 記事ファイル存在チェック
3. validation 実行（`node src/index.js post <file>` を dry-run で実施。`--force` なしで失敗を検知）
4. thumbnail 生成（任意）
5. `node src/index.js post-pair <file-ja>` で公開
6. IndexNow ping（任意）
7. 結果を PR コメントに投稿（`github-script`）

## 5. 実装タスク

- [x] `.github/workflows/publish-article.yml` 作成
- [ ] GitHub に Secrets を設定（`WP_API_URL`, `WP_USER`, `WP_APP_PASSWORD`, 他）
- [ ] 本番 WordPress で Application Password 発行確認
- [ ] `workflow_dispatch` での手動公開を 1 記事で試す
- [ ] ChatGPT/Codex に「draft を作ったら PR を出し、公開は workflow_dispatch で」と指示する手順を AGENTS.md / KB に追記

## 6. 注意点

- 記事内の画像は `post` 処理時に自動で WordPress へアップロードされる（PIPELINE_MANUAL.md Step 3 参照）
- 画像バイナリは PR の `images/` に含める
- 予約投稿は `schedule` コマンドが別途必要（本 workflow では即時公開を想定）
- `post-pair` は `-jp.md` / `-ja.md` / `-en.md` の命名が必須
