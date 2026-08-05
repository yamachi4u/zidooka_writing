---
created: 2026-06-23
status: running
verify_date: 2026-07-07
title: zdk_header_image server-side A/B 開始
---

## 判断
`zdk_header_image`（記事ヘッダー画像サイズ比較）を server-side body class 方式で開始。

## 変更内容
- functions.php: body_class filter で `zdk-header-image-small` / `zdk-header-image-control` を cookie 固定で割当
- posthog-experiments.js: `getServerVariant('zdk_header_image')` 追加、init/forceInit でハンドリング
- CSS: `.zdk-header-image-small .zenn-featured-image { max-width: 600px; ... }`

## 根拠
- パイプラインの次優先度（現状 code_fold と同時稼働だが交絡しない UI 要素）
- server-side 方式が確立したので低コストで開始可能
- 視覚的インパクトとスクロール深度への影響を測定

## 期待する効果
- small: 画像が小さくなることで記事本文のファーストビュー到達が早くなり、read_depth 改善
- もしくは large: 視覚的インパクト維持でエンゲージメント維持

## 検証日
2026-07-07（2週間後に impressions + アウトカムを確認）

## 結果（事後記入）
- 検証日 (2026-07-09): 実験クローズ
  - 16日間のデータ: control=1105 / treatment=1120 impressions
  - Read Depth lift: -6.9%, Engaged 60s: -9.1% → **有意差なし**
  - 判定: inconclusive — ヘッダー画像サイズの大小は読者の行動に影響を与えていない
  - PostHog flag `zdk_header_image` を inactive に設定
