---
created: 2026-06-23
status: running
verify_date: 2026-07-07
title: zdk_author_pos server-side A/B 開始
---

## 判断
`zdk_author_pos`（著者プロフィールの表示）を server-side body class 方式で開始。

## 変更内容
- control: 現在のフル表示（名前、説明、料金、CTAボタン）
- compact: 説明・料金・名前を非表示、CTAボタンのみ中央寄せ

## 根拠
- パイプラインの次優先度
- 3実験同時稼働だが、code_fold/header_image/author_pos は独立したUI要素で交絡しない
- server-side 方式で低コスト実装

## 期待する効果
- compact: 視覚的ノイズ低減でスクロール深度改善、CTAクリック率改善
- control: 現状維持

## 検証日
2026-07-07（2週間後に確認）

## 結果（事後記入）
-
