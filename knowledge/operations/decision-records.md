---
type: concept
description: 判断記録 (Decision Records) のフォーマットと運用ルール
tags: [operations, decisions, adr]
updated: 2026-06-30
links:
  - improvement-cycle.md
---

# 判断記録 (Decision Records)

## 目的

エージェントの判断を記録し、後続のエージェントが同じ判断を繰り返さず、
かつ判断の効果を検証できるようにする。

## ファイル形式

`docs/decisions/YYYY-MM-DD-slug.md` に YAML frontmatter 付き Markdown で記録。

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

## 自動チェック

`npm run decisions` で検証日超過の判断記録を自動検出する。

### 検出された場合の手順

1. 該当 decision record を読む
2. PostHog/GA4 データで効果を確認
3. 「結果」欄に追記
4. status を `completed` に更新（必要なら verify_date を延長）

## ライフサイクル

```
pending → running → completed
                  → (verify_date 超過) → データ確認 → completed または running 延長
```
