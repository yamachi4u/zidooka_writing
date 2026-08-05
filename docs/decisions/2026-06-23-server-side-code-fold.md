---
created: 2026-06-23
status: running
verify_date: 2026-06-30
title: zdk_code_fold server-side A/B assignment 導入
---

## 判断
`zdk_code_fold` の割当を PostHog feature flag から server-side body class + cookie に移行。

## 変更内容
- functions.php: `add_filter('body_class', ...)` で variant をランダム割当 + cookie固定 (90日)
- posthog-experiments.js: `getServerVariant()` が body class を優先参照、PostHog flag は fallback
- forceInit, init の両方で body class を先にチェック
- PostHog の `zdk_code_fold` flag は active のまま残す（analytics 用）

## 根拠
- flag_resolution_error が週214件。forceInit fallback でカバーしていたが、正しい variant 割当ができていないユーザーが多数
- server-side なら PHP レンダリング時に即座に variant が決まり、JSの非同期解決待ちが不要
- cookie でユーザーごとに固定するので一貫性も保てる

## 期待する効果
- impressions が outcome events に追いつく
- null rate 0% が維持される
- 実験の信頼性が向上する

## 検証日
2026-06-30（1週間後に impressions 増加と null rate を確認）

## 結果（事後記入）
- 検証日 (2026-06-30): PostHog 確認
  - Null rate: 0.0% ✅（サーバーサイド割当により完全解消）
  - Impressions: control=970 / treatment=1000（目標200/variant 達成）
  - Outcome events: control=0 / treatment=4（まだ不足 — 週100目標未達）
  - 判断の正しさ: **正しい** — null rate 0% を維持、impressions も十分蓄積
  - 次のアクション: outcomes が溜まるまで継続計測。1週間後に再チェック

- 検証日 (2026-07-09): 実験クローズ
  - 17日間のデータ: control=800 / treatment=740 impressions
  - Read Depth lift: +2.0%, Engaged 60s: -0.3% → **有意差なし**
  - 判定: inconclusive — コードブロック折りたたみは読者の行動に影響を与えていない
  - PostHog flag `zdk_code_fold` を inactive に設定
