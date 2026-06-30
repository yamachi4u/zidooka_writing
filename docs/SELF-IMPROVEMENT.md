# Self-Improvement System (zidooka_writing)

## サイクル

```
[1] エージェントが運用枠組を改善
         ↓
[2] 改善された枠組で記事/テーマ/実験を改善
         ↓
[3] ユーザーの行動データ (PostHog/GA4) が蓄積
         ↓
[4] エージェントがデータを分析し、[1] の判断を評価
         ↓
[5] 評価を元に運用枠組をさらに改善 → [1] へ
```

## 判断記録 (Decision Records)

`docs/decisions/YYYY-MM-DD-slug.md` に記録。
次のエージェントは直近の判断記録を読んでから行動する。

### テンプレート

```markdown
---
created: YYYY-MM-DD
status: pending|running|completed
verify_date: YYYY-MM-DD
title: 判断タイトル
---

## 判断
何をしたか（一言）

## 根拠
なぜそう判断したか

## 期待する効果
これによって何が改善されるはずか

## 検証日
YYYY-MM-DD（この日までに効果を確認する）

## 結果（事後記入）
-
```

## 定例トリガー

エージェントは以下のタイミングで自律的に自己改善アクションを実行する：

| トリガー | アクション | 成果物 |
|---------|-----------|--------|
| 毎回のセッション開始時 | `npm run decisions` で検証日超過の判断がないか確認 | 必要な場合、PostHog/GA4データで検証 |
| 週1回 | `npm run improve` を実行 | `daily/self-improvement/YYYY-MM-DD-self-improvement.md` |
| 実験開始から7日経過 | 実験判定 + adopt/continue/stop の判断 | 判断記録 + コード変更 |
| 判断記録の検証日到達 | `npm run decisions` が検出。PostHogで確認し結果を追記 | 判断記録の「結果」欄 |
| 30日ごと | 過去の全判断を振り返り、正答率を計算 | `daily-agent/retro-YYYY-MM-DD.md` |

## アラート条件

| メトリクス | 閾値 | 超過時のアクション |
|-----------|------|------------------|
| PostHog null rate | 30% | 計装トラブルシュート |
| flag_resolution_error (週) | 50件 | フラグ配信の調査 |
| 実験のdecision deadline超過 | 3日 | 強制的にadopt/stop判断 |

## フィードバックルール

結果が期待通り → 判断基準を維持
結果が期待以下 → なぜズレたかを分析し、判断基準を修正
結果が不明 → データ不足。さらに待つか計測方法を改善
