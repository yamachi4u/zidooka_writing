---
title: "GASで「実行制限・時間切れ・内部エラー」が出たときの全体マップ（原因別チェックリスト付き）"
date: 2025-12-18 13:00:00
categories: 
  - Summary
  - GAS
tags: 
  - Google Apps Script
  - Troubleshooting
  - Error Handling
status: publish
slug: gas-error-map-summary-jp
---

GASで定期処理・集計・API連携を運用していて、急に落ちて困っている人へ。

*   エラー文は読めるけど「結局どう直す？」が欲しい
*   できれば設計から見直して“再発しない形”にしたい

この記事では、GASで発生する「実行制限・時間切れ・内部エラー」を体系的に整理し、エラー文を見た瞬間に“どのカテゴリの問題か”を特定して最短で復旧するための全体マップを提供します。

## まず結論：GASの落ち方は4系統に分かれる（全体マップ）

GASのエラーは、大きく分けて以下の4つに分類できます。

### A) 呼び出し回数・頻度系（クォータ/レート）
*   **代表エラー:** `Exception: Service invoked too many times`
*   **本質:** 短時間に同一サービスを叩きすぎ / トリガー多発 / ループで呼びすぎ
*   **詳細記事:** [Google Apps Script の “Exception: Service invoked too many times” が急増している理由と、現場で使える対処法](https://www.zidooka.com/archives/654)

### B) 実行時間オーバー系（時間切れ）
*   **代表エラー:** `Exceeded maximum execution time`（6分制限など）
*   **本質:** 1回の実行に詰め込みすぎ / 取得件数が増えていつか死ぬ設計
*   **詳細記事:** [GASで“Exceeded maximum execution time”が出たときの原因と対処法【6分制限対策】](https://www.zidooka.com/archives/687)

### C) 外部API応答待ち系（タイムアウト/締切超過）
*   **代表エラー:** `DEADLINE_EXCEEDED`
*   **本質:** 相手側が遅い/不安定、ネットワーク、Google側の内部締切、巨大レスポンス、リトライ不足
*   **詳細記事:** [Google Apps Script｜「DEADLINE_EXCEEDED」エラーをやさしく解説します](https://www.zidooka.com/archives/670)

### D) 内部エラー系（理由が見えにくい）
*   **代表エラー:** `INTERNAL`（内部エラー）
*   **本質:** Google側の一時不調・サービス内部例外・再実行で通ることがある“運用系の敵”
*   **詳細記事:** [Google Apps Script の “INTERNAL（内部エラー）” が突然出たときの原因と対処まとめ](https://www.zidooka.com/archives/675)

## エラー文から逆引き：まずここだけ見れば判定できる

| エラー文に含まれるキーワード | 分類 | 原因の方向性 |
| :--- | :--- | :--- |
| `Service invoked too many times` | **A (頻度/回数)** | 短時間の集中アクセス、ループ内のAPI呼び出し |
| `Exceeded maximum execution time` | **B (時間)** | 処理量が多すぎる、無限ループ、待機時間が長い |
| `DEADLINE_EXCEEDED` | **C (外部/締切)** | 外部APIが遅い、レスポンスが巨大、通信不安定 |
| `INTERNAL` | **D (内部)** | Google側の不調、一時的な障害、原因不明 |

**発生タイミングでの追加判定:**

*   「夜間バッチだけ落ちる」→ 件数増加・API側混雑の可能性大
*   「同じ入力でたまに通る」→ INTERNAL / DEADLINE寄りの可能性
*   「スプレッドシート操作が多い」→ invoked too many times寄りの可能性

## 一次対応（止血）チェックリスト：今日復旧させるための手順

### A) invoked too many times の止血
*   ループ内の `SpreadsheetApp` / `GmailApp` / `DriveApp` / `UrlFetchApp` を数える
*   `getRange()` をループで呼ぶのを止める（まとめて取得→配列処理）
*   `Utilities.sleep()` は最後の手段（治らないことが多い）
*   トリガーの多重起動を疑う（同時実行で地獄を見る）
*   👉 [詳細な対処法はこちら](https://www.zidooka.com/archives/654)

### B) exceeded maximum execution time の止血
*   “処理を分割できるか”が全て：バッチ化（N件ずつ）
*   `PropertiesService` にカーソル（進捗）を持つ
*   失敗しても次回再開できるようにする（冪等性）
*   まずは「処理対象を減らして通す」→ その後設計修正
*   👉 [詳細な対処法はこちら](https://www.zidooka.com/archives/687)

### C) DEADLINE_EXCEEDED の止血
*   UrlFetchは「遅い前提」で設計：タイムアウト前に諦める発想
*   リトライ（指数バックオフ）
*   レスポンスを小さく（fields指定、ページング）
*   同時アクセスを減らす
*   👉 [詳細な対処法はこちら](https://www.zidooka.com/archives/670)

### D) INTERNAL の止血
*   まず再実行（ただし自動化するならリトライ制御）
*   status dashboard を見たくなるが、現場では“再実行設計”が勝ち
*   依存サービスを切り分ける（Drive/Spreadsheet/UrlFetchどこが怪しいか）
*   👉 [詳細な対処法はこちら](https://www.zidooka.com/archives/675)

## 恒久対策：運用で死なないGAS設計の共通ルール

各記事に共通する“上位原理”です。

1.  **1回で全部やらない（分割前提）**
    *   N件ずつ処理し、状態を保存して次回継続する。
2.  **冪等性（何回やっても整合する）を作る**
    *   “一発成功”を捨てて、“何度でも整う”設計にする。
3.  **外部I/Oはまとめる**
    *   シート読み書きはまとめて行う。UrlFetchは並列にしすぎない。
4.  **ログと観測を最初から仕込む**
    *   何件処理したか、どこで止まったか、復旧できる情報を残す。
5.  **トリガーの同時実行を前提にガードする**
    *   LockService / 二重起動防止 / キュー構造を利用する。

## よくある“勘違い”を潰す

*   **sleepすれば解決する？** → 一部しか効きません。構造が悪いと逆に制限に引っかかります。
*   **実行時間は“最適化”で伸ばす？** → 限界があります。分割処理が正解です。
*   **INTERNALは自分のコードが悪い？** → 悪い場合もありますが、運用設計でカバーするのが9割です。

## まとめ

エラー文を見たら、まずはこのマップに戻ってきてください。

1.  **逆引き表**でカテゴリを特定
2.  該当カテゴリの**一次対応**を実施
3.  **詳細記事**で深掘り
4.  最後に**恒久対策テンプレ**を適用

このフローを守るだけで、GASのトラブルシューティングは劇的に早くなります。
