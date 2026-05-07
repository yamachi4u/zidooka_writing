---
title: "Veo3のウォーターマークありなし早見表（2026/02/14時点）"
date: 2026-02-14 18:40:00
categories:
  - AI
tags:
  - Veo 3
  - Flow
  - Gemini
  - Google AI
  - ウォーターマーク
status: publish
slug: veo3-watermark-hayami-20260214
featured_image: ../images/2026/02/veo3-watermark-hayami-20260214-thumbnail.png
---

:::conclusion
2026年2月14日時点で、Google公式が明確に「可視ウォーターマークなし」と書いているのは、`Flow`で`Google One Ultra`ユーザーが生成した動画です。
:::

この記事は、Google公式ページのみを根拠に、`Geminiアプリ`と`Flow`を分けて整理しています。

## 先に要点

:::note
可視ウォーターマークのルールは「どのプランか」だけでなく「どこで生成したか（GeminiアプリかFlowか）」で変わります。
:::

:::warning
不可視の`SynthID`は別です。可視ウォーターマーク有無に関わらず、Google生成コンテンツにはSynthID埋め込みが基本です。
:::

## Geminiアプリ（gemini.google.com / Gemini mobile）

:::conclusion
`Geminiアプリ`で生成する動画は、プランに関係なく可視ウォーターマークありの扱いです（公式ブログの例外条件より）。
:::

| プラン | 可視ウォーターマーク | 根拠 |
| --- | --- | --- |
| Google AI Plus | あり | Google公式ブログの「FlowのUltraメンバー生成以外は可視ウォーターマーク追加」方針 |
| Google AI Pro | あり | 同上 |
| Google AI Ultra | あり | 例外は「Ultra members in Flow」のみと明記されているため |
| Workspaceライセンス経由のGeminiアプリ | あり（推定） | 公式の例外文言がFlow限定のため |

※Geminiアプリのヘルプは主に利用条件と上限を説明しており、可視ウォーターマークの詳細は公式ブログ側のルールが一次情報です。

## Flow（labs.google / flow.google.com）

:::conclusion
`Flow`では、Google公式ヘルプに「Proは可視あり」「Ultraは可視なし」が明記されています。
:::

| プラン | 可視ウォーターマーク | 根拠 |
| --- | --- | --- |
| Google One Pro | あり | Flow Help FAQで明記 |
| Google One Ultra | なし | Flow Help FAQで明記 |
| Google AI Ultra for Business（AI Ultra Access） | 公式明記なし | Workspace/Flowの公開ヘルプに可視WMの明文を確認できず |
| 通常Workspace対象プラン（Flow 100 credits） | 公式明記なし | 同上 |

:::warning
Flowの公開ヘルプで明示されている可視WM条件は、文言上`Google One Pro/Ultra`です。Workspace系は同ページで利用資格は書かれていますが、可視WM条件は個別明記されていません。
:::

## 「Plusはあるの？ないの？」の整理

:::note
Google Oneの`Google AI Plans`ページには`Plus / Pro / Ultra`が掲載されています。
:::

一方で、Flowヘルプの利用条件は`Pro / Ultra / Ultra for Business`表記です。  
この差は、地域・ロールアウト段階・ヘルプ更新タイミングの違いで発生している可能性があります。

:::step
契約前は次の2つを同時に確認してください。
1. `one.google.com/about/google-ai-plans`（自分の国表示での提供プラン）
2. `support.google.com/flow/answer/16353333`（Flow側の最新利用条件とFAQ）
:::

## 公式ソース（2026/02/14確認）

1. Google公式ブログ（Veo 3拡大告知、可視WMの例外条件）
https://blog.google/products/gemini/veo-3-expansion-mobile/

2. Flow Help（Flowの可視/不可視ウォーターマークFAQ）
https://support.google.com/flow/answer/16353333

3. Gemini Apps Help（動画生成機能の利用条件）
https://support.google.com/gemini/answer/16126339

4. Google One AI Plans（Plus/Pro/Ultraの現行掲載）
https://one.google.com/about/google-ai-plans/

5. Workspace Admin Help（FlowのWorkspace対象プラン）
https://support.google.com/a/answer/16766194
