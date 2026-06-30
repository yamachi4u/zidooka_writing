---
type: concept
description: zidooka_writing プロジェクト詳細
tags: [project, blog, wordpress, zidooka]
updated: 2026-06-30
links:
  - ../operations/improvement-cycle.md
  - ../analytics/posthog.md
  - ../reference/troubleshooting.md
---

# zidooka-writing

## 概要

WordPress ブログサイト `zidooka.com` のコンテンツ運用・テーマ開発・A/Bテスト・SEO を行うプロジェクト。

- **ディレクトリ**: `C:\Users\user\Documents\zidooka_writing`
- **主要コマンド**: `npm run post`, `npm run posthog:check`, `npm run weekly`, `npm run improve`
- **テーマ**: zidooka-tw (Tailwind CSS v4)

## 運用パイプライン

### 公開 (Publishing)

| コマンド | 用途 |
|---------|------|
| `node src/index.js post drafts/file.md` | 記事公開（バリデーション付き） |
| `node src/index.js post drafts/file.md --force` | バリデーションスキップ |
| `node src/index.js post-pair drafts/file-ja.md` | 日英ペア公開 |
| `node scripts/ping-indexnow.mjs <url>` | IndexNow 通知 |

原則として日本語 + 英語のペア公開がデフォルト。

### A/B テスト (PostHog)

- フラグキー: `zdk_*` プレフィックス
- サーバーサイド割当 (body class + cookie) を採用
- 同時稼働: 1本まで（最大2本、同じ目的の場合のみ）
- 詳細: [analytics/posthog.md](../analytics/posthog.md)

### テーマ管理

- ローカル編集: `downloads/zidooka-tw/`
- リモート反映: `node scripts/remote-agent/index.js push`

## 改善サイクル

- `npm run improve`: 自己改善ループ実行（GA4/GSC/AdSense/Bing/PostHog データ収集 → TODO生成）
- `npm run decisions`: 検証日超過の判断記録を自動チェック
- 判断記録: `docs/decisions/`
