---
title: "【Gemini API】Function response parts mismatch エラーの原因と対処法"
date: 2026-01-11 12:00:00
categories: 
  - AI
  - AI系エラー
  - Copilot &amp; VS Code Errors
tags: 
  - Gemini
  - Gemini CLI
  - Error
  - API
  - Function Calling
status: publish
slug: gemini-function-response-parts-error-jp
featured_image: ../images/gemini-error-thumb.png
---

Gemini CLI や VS Code (Agent Mode)、Cursor などのユーザーの間で、`INVALID_ARGUMENT` エラーが頻発しています。これは、関数呼び出し（Function Calling）を含む会話履歴の不整合によって発生します。

## エラー内容

以下のようなJSON形式のエラーが表示され、応答が止まります。

```json
{
  "error": {
    "code": 400,
    "message": "Please ensure that the number of function response parts is equal to the number of function call parts of the function call turn.",
    "status": "INVALID_ARGUMENT"
  }
}
```

## 原因

このエラーは、Gemini API とクライアント間の会話履歴の不整合が原因です。

- 【結論】モデルによる「ツール使用」の履歴が同期ズレを起こしている場合に発生します。
- 【ポイント】モデルが生成した「関数呼び出し」の数と、クライアントが返した「関数の実行結果」の数が一致していない（または順序が狂っている）ことに対する警告です。
- 【注意】長いコンテキストを持つセッションや、IDEの「Agent」モードなどで、履歴管理が複雑になった際によく発生します。

## 対処法

これはアプリ側のAPI利用履歴の問題であるため、ユーザー側で修正するのは困難です。

> 【結論】現在のチャットセッションの履歴をクリアするしか確実な方法はありません。
> Gemini CLI なら `/clear` コマンド、VS Code/Cursor なら「新しいチャット」を開始してください。

GitHub Issue (#2347, #1690) でも多数報告されており、根本的な解決が待たれています。
