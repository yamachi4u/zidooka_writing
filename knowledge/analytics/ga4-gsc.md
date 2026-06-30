---
type: concept
description: GA4・GSC 分析の詳細
tags: [analytics, ga4, gsc, seo]
updated: 2026-06-30
links:
  - index.md
---

# GA4 / GSC

## 共通コマンド

```powershell
# GA4
npm run ga4 -- --preset overview|acquisition|landing-pages|events|countries
npm run ga4 -- --period 7d|30d

# GSC
npm run gsc -- --preset top-queries|top-pages
npm run gsc -- --preset top-queries --limit 50 --start-date YYYY-MM-DD --end-date YYYY-MM-DD

# SEO レポート
npm run seo:weekly       # GA4 + GSC 7日分
npm run seo:monthly      # GA4 + GSC 30日分
npm run seo:errors       # エラーページ抽出
```

## 監視閾値

| 指標 | 健全 | 警告 |
|------|------|------|
| RPM | ¥170+ | <¥100 |
| Desktop fill rate | 70%+ | <60% |
| CTR (GSC) | 5%+ | <3% (100imp+の場合) |
| Engagement rate | 30%+ | <25% (50sessions+の場合) |

## 注意点

- GSC の `top-queries` は zidooka.com（デフォルト）と tools.zidooka.com（`--site` 指定）で別々に取得
- GA4 は Service Account 認証（`google-api-common.mjs`）
- 日付範囲は最低でも直近28日を取るとトレンドが安定する
