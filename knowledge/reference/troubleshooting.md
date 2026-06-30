---
type: concept
description: 既知の問題と解決策
tags: [reference, troubleshooting, known-issues]
updated: 2026-06-30
---

# Troubleshooting

## PostHog 関連

### Null rate が高い
- **原因**: `posthog.getFeatureFlag()` の非同期解決が遅い
- **zidooka の解決策**: Server-side body class + cookie で対応済み
- **benri-tools**: forceInit fallback (timeout 15s, MAX_FALLBACK 20) で対応
- **確認**: `npm run posthog:check` (zidooka) / `npm run ph:flags` (benri-tools)

### フラグ削除が 405
- PostHog API が DELETE を許可していない
- 代わりに PATCH で `filters.multivariate.variants` の `rollout_percentage` を設定
- multivariate flag の停止: `variants` で `control: 100, v1: 0` に設定

## Netlify / インフラ

### GitHub 連携が切れている
```powershell
git push && netlify deploy --prod --trigger
```

### Windows ローカルの edge function bundling が壊れている
- サーバーサイドビルド必須
- ローカルでは `npm run dev` のみ使用

### AdSense トークン期限切れ
```powershell
npm run adsense:setup
```

## よくあるエラー

| 症状 | 原因 | 解決策 |
|------|------|--------|
| `tool_error` 多発 | 各ツールのエラー | 内訳を確認後、該当ツール修正 |
| `ad_empty` 多発 | 広告在庫不足 | 広告スロット配置確認 |
| Bing crawl error 多発 | 404/5xx | エラーページ特定後修正、robots.txt 確認 |
| GSC CTR 低下 | メタデータ不足 | タイトル・description 改善 |
