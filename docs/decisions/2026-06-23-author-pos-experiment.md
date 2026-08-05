---
created: 2026-06-23
status: deferred
verify_date: 2026-07-23
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
- 2026-07-09: 実施延期
  - PostHog flag 未作成。code_fold + header_image の2実験が inconclusive で閉じたため、パイプライン次候補の `zdk_author_pos` は後日開始予定
  - トリガー: サイドバー広告A/Bテスト (7/23判定) の結果を待ってから開始しても良い
