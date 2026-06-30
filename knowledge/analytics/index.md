---
type: section
description: GA4/GSC/AdSense/Bing/PostHog 分析チャネル
tags: [analytics, data, metrics]
updated: 2026-06-30
---

# Analytics

データ分析チャネルの一覧と運用方法。

## チャネル一覧

| チャネル | コマンド | 認証方式 |
|---------|---------|---------|
| [GA4](./ga4-gsc.md) | `npm run ga4` | Service Account |
| [GSC](./ga4-gsc.md) | `npm run gsc` | Service Account |
| [AdSense](./adsense.md) | `npm run adsense` | OAuth Desktop |
| [Bing](./bing.md) | `npm run bing` | API Key |
| [PostHog](./posthog.md) | `npm run posthog:check` (zidooka) / `npm run ph:status` (tools) | Personal API Key |

## 週次リズム

| 頻度 | タスク | コマンド |
|------|-------|---------|
| Mon/Thu | PostHog A/B Check | `npm run posthog:check` (zidooka) |
| 週1 | 統合レポート | `npm run weekly` |
| 週1 | 低CTR記事チェック | `npm run gsc -- --preset top-queries --limit 30` |
| 週1 | 自己改善ループ | `npm run improve` |
| 隔週 | GSC ギャップ分析 | `npm run seo:errors` |
| 月1 | 記事パフォーマンスレビュー | `npm run seo:monthly` |
