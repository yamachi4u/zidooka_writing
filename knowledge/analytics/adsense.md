---
type: concept
description: AdSense 分析・運用
tags: [analytics, adsense, ads, revenue]
updated: 2026-06-30
links:
  - index.md
  - posthog.md
---

# AdSense

## コマンド

```powershell
npm run adsense                              # 日次レポート
npm run adsense -- --dimensions PLATFORM_TYPE_CODE  # デバイス別
npm run adsense:setup                        # OAuth トークン再発行
```

## 監視指標

| 指標 | 健全 | 警告 | アクション |
|------|------|------|----------|
| RPM | ¥170+ | <¥100 | 広告設定確認 |
| Desktop fill rate | 70%+ | <60% | 在庫確認 |
| RPM drop (週) | ±10%以内 | 20%以上低下 | トレンド調査 |

## 注意点

- OAuth Desktop 認証を使用。トークン期限切れ時は `npm run adsense:setup` で再発行
- アドセンスのクリックは cross-origin iframe 内のため直接取得不可。`AdSlot` ラッパーの `onAdClick` をプロキシ指標として使用
- セッションあたり最大3つの広告表示に制限
