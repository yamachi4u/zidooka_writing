---
title: "Geminiでよく出るエラーまとめ【(5) / 応答停止 / 読み込み失敗】"
date: 2026-04-10 09:00:00
categories:
  - summary
  - AI系エラー
tags:
  - Gemini
  - Error Summary
  - Troubleshooting
  - Google AI Studio
status: future
slug: gemini-errors-summary-20260410
---

Gemini のエラーは、画面上ではどれも「動かない」「止まる」「読めない」に見えますが、実際には ==モデル側不安定==、==ブラウザやセッション不整合==、==API上限== のように原因が分かれています。

個別記事はすでにいくつかあるので、この記事では Gemini 系でよく見る文言をまとめて、どこから確認すればいいかを整理します。

:::conclusion
Gemini 系は `内部エラー`、`ストリーム中断`、`クォータや課金上限`、`function calling の不整合` の4系統に分けるとかなり見やすくなります。
:::

## まずは文言で切り分ける

| 表示される文言 | 原因の本命 | 先にやること | 詳細記事 |
| --- | --- | --- | --- |
| `エラーが発生しました。(5)` | ブラウザ側セッション不整合、モデル側の一時不安定 | モデル切替、シークレットモード、スマホアプリ確認 | [Geminiで「エラーが発生しました。(5)」が出る原因と対処法](https://www.zidooka.com/archives/621) |
| `Server error. Stream terminated` | モデル側の応答停止、Preview 系モデルの不安定さ | モデル変更、再試行 | [Gemini 3 Pro で「Server error. Stream terminated」が出たときの原因](https://www.zidooka.com/archives/1219) |
| `Resource has been exhausted` | クォータ、課金、レート制限 | 使用量確認、並列削減、課金設定確認 | [OpenCode-Geminiで『Resource has been exhausted』が出る原因と対処法](https://www.zidooka.com/archives/3510) |
| `Please ensure that the number of function response parts...` | function calling の履歴不整合 | 会話やセッションを切る、ツール呼び出しをやり直す | [Gemini-CLIで発生する「Please ensure that the number of function response parts…」エラー](https://www.zidooka.com/archives/5349) |

## 1. 「エラーが発生しました。(5)」

これは Gemini の中でもかなり汎用的な内部エラーです。

とくに、PC ブラウザ版でだけ起きてスマホアプリでは普通に動く、というパターンが目立ちます。

:::step
`モデル切替`、`シークレットモード`、`Google 関連クッキーの整理` の順で試すのが近道です。
:::

詳しい対処は、こちらです。  
[Geminiで「エラーが発生しました。(5)」が出る原因と対処法](https://www.zidooka.com/archives/621)

## 2. 「Server error. Stream terminated」

これは ==応答を返している途中でストリームが切れた== タイプのエラーです。

Gemini 3 Pro のような不安定なモデルや Preview 段階のモデルで起こりやすく、環境の問題というよりモデル側の状態に寄っていることがあります。

:::note
この系統は、設定を細かくいじるより `モデルを変える` 方が早く直ることが多いです。
:::

詳しくは、こちらです。  
[Gemini 3 Pro で「Server error. Stream terminated」が出たときの原因と、Claude Sonnet 4.5 に切り替えて解決した話](https://www.zidooka.com/archives/1219)

## 3. 「Resource has been exhausted」

これは UI 不調というより、==上限到達系== のエラーです。

Gemini API、CLI、Agent 系ツールを触っていると、使用量、クォータ、同時実行数、課金設定のどれかに当たって止まることがあります。

:::step
`クォータ確認`、`課金上限確認`、`並列実行の削減` を先に見ます。
:::

詳しくは、こちらです。  
[OpenCode-Geminiで『Resource has been exhausted』が出る原因と対処法](https://www.zidooka.com/archives/3510)

## 4. 「Please ensure that the number of function response parts...」

これは少し毛色が違って、Gemini 側というより ==function calling を含む会話履歴の整合性崩れ== で起きるエラーです。

Gemini CLI や Agent Mode、Cursor のようにツール呼び出しを伴う環境で出やすく、会話を引きずりすぎたときにも起きます。

:::step
会話を新しく切り直すか、問題の出た tool call を含む流れをやり直すのが基本です。
:::

詳しくは、こちらです。  
[Gemini-CLIで発生する「Please ensure that the number of function response parts…」エラーについて](https://www.zidooka.com/archives/5349)

## 迷ったときの見分け方

:::step
ブラウザ版だけ不安定なら `(5)` 系を疑います。
:::

:::step
モデル変更で改善するなら `Stream terminated` 系が近いです。
:::

:::step
回数を回したあとに止まるなら `Resource has been exhausted` を先に確認します。
:::

:::step
ツール呼び出しや Agent 実行の途中で壊れるなら `function response parts` 系を見ます。
:::

## まとめ

Gemini のエラーは、全部を「Google 側の不具合」で片付けると遠回りになります。

- `(5)` は内部エラー寄り
- `Stream terminated` は応答停止寄り
- `Resource has been exhausted` は上限到達寄り
- `function response parts` は会話履歴不整合寄り

この切り分けで見るだけでも、次に試すべき対処がかなり明確になります。
