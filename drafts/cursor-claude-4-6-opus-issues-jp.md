---
title: "Cursorで「Claude 4.6 Opus is currently experiencing issues」と出るときの対処法"
categories:
  - AI
tags:
  - Cursor
  - Claude 4.6 Opus
  - high demand
  - AIエラー
status: draft
slug: cursor-claude-4-6-opus-issues-jp
---

Cursor で Claude 4.6 Opus 系を使っていると、`is currently experiencing issues`、`not available in the slow pool`、`high demand` のような文言にぶつかることがあります。

文言は少しずつ違っても、見ている現象はかなり近いです。

:::conclusion
Claude 4.6 Opus 系の `currently experiencing issues` は、Cursor かモデル提供側の利用経路が不安定なときに出やすい表示です。まずは Auto か別モデルへ切り替え、slow pool / usage / 時間帯の影響を先に疑うのが実用的です。
:::

## よく一緒に出る文言

- `Claude 4.6 Opus is currently experiencing issues`
- `is not available in the slow pool`
- `We’re experiencing high demand for the selected model right now`

Cursor の公式 docs では、Auto は current demand を見ながら信頼性の高いモデルを選ぶと説明されています。  
つまり逆に言うと、固定モデルを選んでいると、そのモデルだけの混雑や提供問題を直接踏みやすいです。

## まずやること

### 1. Auto に切り替える

Cursor docs でも Auto は reliability ベースで選ぶとされています。  
この種のエラーでは、一番手堅い回避策です。

### 2. Opus 固定をやめて Sonnet や他モデルへ逃がす

問題が `Opus 固有` なら、モデルを変えるだけで通ることがあります。

### 3. 少し時間をずらす

Cursor Community Forum でも、高負荷時間帯に selected model 系エラーが続く報告があります。  
時間帯依存なら、設定や PC より混雑です。

### 4. usage と plan を確認する

Cursor の pricing docs では、モデルごとに消費の重さが違い、Pro / Pro Plus / Ultra で扱いも変わります。  
Opus 系は軽いモデルより消費が重くなりやすいので、limit 周辺だと不安定に見えることがあります。

## `slow pool` は何を意味するのか

Cursor Forum の報告を見る限り、`not available in the slow pool` は、少なくとも「そのモデルを現在の経路で使えない」ことを示す文言です。  
これはローカルの設定ミスより、モデル提供経路やプール制御を疑うほうが近いです。

## 何から疑うべきでないか

- いきなり再インストール
- OS の初期化
- すべての拡張機能削除

このエラー群は、まずモデル選択と供給状況の話として見るべきです。

:::warning
同じプロンプトを Opus 固定で連打すると、改善確認よりも混雑を踏み続けるだけになりがちです。まずは Auto で通るか確認してください。
:::

## まとめ

Claude 4.6 Opus 系で `currently experiencing issues` が出たら、

- Auto へ切り替える
- 別モデルへ逃がす
- 時間をずらす
- usage と plan を見る

この順が最短です。  
ローカル故障と決めつけるのは後回しで大丈夫です。

## 参考

- [Cursor Models & Pricing](https://docs.cursor.com/account/rate-limits)
- [Cursor Selecting Models](https://docs.cursor.com/en/guides/selecting-models)
- [High load on any cursor model - Cursor Community Forum](https://forum.cursor.com/t/high-load-on-any-cursor-model/153014)
- [Claude-4.6-opus-high-thinking is not available in the slow pool - Cursor Community Forum](https://forum.cursor.com/t/claude-4-6-opus-high-thinking-is-not-available-in-the-slow-pool/154026)

