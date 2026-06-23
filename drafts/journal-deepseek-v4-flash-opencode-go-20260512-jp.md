---
title: "DeepSeek V4 FlashをOpenCode Goで使ってみた。かなり使えるけど画像貼り付けは弱い"
slug: journal-deepseek-v4-flash-opencode-go-20260512-jp
date: 2026-05-12 00:30:00
categories:
  - journal
tags:
  - DeepSeek
  - OpenCode
  - AI
  - CLI
status: publish
featured_image: ../images/2026/05/opencode-go-contract.png
---

OpenCode Go で DeepSeek V4 Flash をしばらく使っています。

結論から言うと、かなりいいです。無限に使える、とまでは言いません。ただ、普通のコーディング作業や調査、リファクタ、軽い実装の相談なら、恐ろしいくらい実用になります。

:::conclusion
DeepSeek V4 Flash は OpenCode Go でかなり使える。弱点は画像貼り付けができないこと。私は GPT-5.5 を司令塔にして、Flash を実働部隊として使う形がちょうどいいと感じています。
:::

## 何がいいのか

まず、速いです。

重いモデルに投げるほどではない作業を、DeepSeek V4 Flash にどんどん流せます。軽い修正、ログの読み取り、ファイルの整理、TODO の分解、既存コードの把握。このあたりはかなり快適です。

OpenCode Go のモデル情報では、DeepSeek V4 Flash は 100万トークン級のコンテキストを持つ text 入力モデルとして扱われています。価格もかなり軽いので、細かく何度も投げる使い方と相性がいいです。

- ざっくり読む
- 差分を見る
- 小さな修正案を出す
- 長めのログを要約する
- エージェント作業の下請けにする

このあたりは本当に強いです。

## 微妙なところ：画像を貼れない

一方で、私にとって一番惜しいのは画像入力です。

スクリーンショットを貼って「ここ見て」と言いたい場面は多いです。UI の崩れ、エラー画面、管理画面、グラフ、設定画面。こういうものをそのまま渡せないのは、やはり少し不便です。

調べた範囲でも、OpenCode Go 上の DeepSeek V4 Flash は text input / text output のモデルとして掲載されています。

つまり、文章・コード・ログには強いけれど、スクショを見せて判断させる用途は別モデルに任せる、という割り切りが必要です。

## 私の使い方

今は GPT-5.5 を司令塔にしています。

大きな方針、複数エージェントの整理、最終判断、画像を含む確認は GPT-5.5 に寄せる。そのうえで、DeepSeek V4 Flash には OpenCode Go 経由で細かい実働タスクをどんどん投げる。

この分担がかなりいいです。

GPT-5.5 をメインの判断役にして、DeepSeek V4 Flash を速い作業者として使う。人間で言うと、司令塔と手数の多い実装担当を分ける感じです。

## 調べたメモ

DeepSeek V4 Flash は、公開情報では 284B total / 13B active の MoE モデル、100万トークン級のコンテキスト、低価格な入出力単価が特徴として紹介されています。

OpenRouter では `deepseek/deepseek-v4-flash` として、1,048,576 context、$0.14/M input、$0.28/M output と掲載されています。

OpenCode Go 向けのモデル一覧でも、DeepSeek V4 Flash は `deepseek-v4-flash` / `deepseek-flash` として、text input、text output、tool calling、reasoning、structured output 対応のモデルとして整理されています。

参考：

- OpenRouter: https://openrouter.ai/deepseek/deepseek-v4-flash
- whichllm OpenCode Go listing: https://whichllm.io/models/opencode-go-deepseek-v4-flash
- whichllm DeepSeek listing: https://whichllm.io/models/deepseek-deepseek-v4-flash

## まとめ

DeepSeek V4 Flash は、かなり実用的です。

ただし、画像貼り付けができない点ははっきり弱点です。スクショを見せながら進める作業では、GPT-5.5 や画像対応モデルを使ったほうがいいです。

一方で、テキスト・コード・ログ中心の作業なら、OpenCode Go の DeepSeek V4 Flash はかなり頼れます。

私の今の結論はこれです。

- 司令塔：GPT-5.5
- 実働：DeepSeek V4 Flash
- 画像確認：画像対応モデル

この組み合わせはかなり強いです。
