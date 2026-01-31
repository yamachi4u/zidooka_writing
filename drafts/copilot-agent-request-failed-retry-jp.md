---
title: "Copilot Agent「Sorry, your request failed」→リトライしたら普通に動いた話"
date: 2026-01-14 23:00:00
categories:
  - Copilot &amp; VS Code Errors
tags:
  - Copilot Agent
  - GitHub Copilot
  - VS Code
  - エラー報告
status: publish
slug: copilot-agent-request-failed-retry-jp
featured_image: ../images/2025/image copy 37.png
---

## 発生したエラー

VS CodeでCopilot Agentを使っていたら、突然こんなエラーが出ました。

```
Sorry, your request failed. Please try again.

Copilot Request id: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx

GH Request Id: XXXXXXXXX

Reason: Error on conversation request. Check the log for more details.
```

## 対処法

【結論】何もしなくていい。**Try Again**ボタンを押したら普通に動いた。

## 原因（推測）

おそらくサーバー側の一時的な負荷。Claude 4.5 Opusのような重いモデルを使っていると、たまに発生する模様。

## 体感

正直、1日中Copilot Agent（Claude 4.5 Opus）を使い倒していて、このエラーが出たのは1回だけ。しかもリトライで即復旧。

【ポイント】焦らずリトライすればOK。エラーログを深追いする必要なし。
