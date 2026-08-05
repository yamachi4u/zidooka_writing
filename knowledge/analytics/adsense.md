---
type: concept
description: AdSense 分析・運用
tags: [analytics, adsense, ads, revenue]
updated: 2026-07-07
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
- アドセンスのクリックは cross-origin iframe 内のため直接取得不可。iframeへのフォーカス移動を
  プロキシとして PostHog `ad_click` を送信（2026-07-07〜、`inc/ads.php`）
- PostHogイベント: `ad_impression` / `ad_click` / `ad_unfilled`（placement別。fill率監視は ad_unfilled）
- 広告の配置・設定は WP管理画面 [外観 > Ads Settings]（台帳: テーマ `inc/ads.php`、
  設計: `docs/ADS_MANAGEMENT.md`）
- セッションあたり最大3つの広告表示に制限
