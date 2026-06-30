---
type: concept
description: Bing Webmaster 分析・運用
tags: [analytics, bing, webmaster, crawl]
updated: 2026-06-30
links:
  - index.md
---

# Bing Webmaster

## コマンド

```powershell
npm run bing -- --preset crawl-stats|top-queries|rank-traffic
```

## 監視指標

| 指標 | 健全 | 警告 | アクション |
|------|------|------|----------|
| Crawl error rate | <30% | >30% | robots.txt, 404確認 |
| CTR | 5%+ | 3%未満 | メタデータ改善 |

## 注意点

- API Key 認証
- IndexNow: `npm run indexnow` で通知（デプロイ後必須）
- IndexNow Key: `public/8ba925c14f67de03.txt`
