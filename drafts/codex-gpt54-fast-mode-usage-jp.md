---
title: "Codexの残量が急に減った本当の原因は GPT-5.4 extra high と Fast mode だった話"
slug: codex-gpt54-fast-mode-usage-jp
date: 2026-03-17 21:10:00
categories:
  - AI系エラー
tags:
  - Codex
  - OpenAI
  - GPT-5
  - レート制限
  - Fast mode
status: publish
description: "Codexの残量が急に減って障害を疑ったあと、設定を見直したら GPT-5.4 の重い構成と Fast mode が主因だった、という後日談です。"
---

以前、[Codexのレートリミット、ほぼ使っていないのに4%になっていた話](https://www.zidooka.com/archives/3997) という短いメモを書きました。

あの時点では、正直かなり「OpenAI側の不具合かも」と疑っていました。  
実際、その疑い自体は完全な見当違いでもありませんでした。

OpenAIの公式ステータスでは、**2026年3月6日から3月7日** にかけて、Codexの使用量が想定より速く消費される不具合が案内されていました。

ただ、あとから自分の設定を見直すと、**もっと単純なオチ** もありました。

:::conclusion
結局、私の環境では `GPT-5.4` の重い設定に加えて `Fast mode` まで有効になっていたのが大きかったです。特に `Fast mode` は、OpenAI公式で **速度1.5倍・クレジット消費2倍** と案内されています。
:::

## 最初に疑っていたこと

当時の感覚としては、そこまで大量に使ったつもりがないのに、残量表示だけが急に減っていました。

そのため、

- 表示の遅延
- 集計のズレ
- 一時的なサービス障害

このあたりをまず疑っていました。

実際、OpenAI Status には **"Issues with Increased Codex Usage Rate"** という障害情報が出ていて、内容も「想定より速く使用量が消費され、一部ユーザーが予定より早くレートリミットに当たる可能性がある」というものでした。

なので、あの時点で障害を疑った判断自体はそこまで外れていません。

## でも、後から見ると自分の設定も重かった

その後に見直して分かったのが、**自分でかなり重い構成を踏んでいた** ことです。

少なくとも、次の2つは消費が重くなりやすい条件でした。

- `GPT-5.4` を使っていた
- `Fast mode` を有効にしていた

さらに、`GPT-5.4` は `reasoning.effort` で `xhigh` を使えるモデルです。  
私が見ていたのも、いわゆる `extra high` 側の重い設定でした。

## `Fast mode` は本当に重い

ここは体感ではなく、公式の案内があります。

OpenAI の Codex ドキュメント `Speed` では、**2026年3月17日時点で `Fast mode` は GPT-5.4 でサポートされており、有効時は速度が1.5倍、クレジット消費は2倍** と説明されています。

つまり、「なんか速い気がする」の裏で、**消費側もちゃんと倍化していた** わけです。

:::warning
ここでいう「2倍」は、厳密には OpenAI の表現では `credits are consumed at a 2x rate` です。雑に「トークンが2倍」と言いたくなりますが、正確にはクレジット消費の話です。
:::

## `GPT-5.4 extra high` も軽くはない

こちらは `Fast mode` のように「ちょうど2倍」と明記された話ではありません。  
ただ、公式情報を見ると、重くなりやすい理由はかなりはっきりしています。

OpenAI Help Center の `Codex rate card` では、平均的なローカルタスクのクレジット消費は以下のように案内されています。

- `GPT-5.4`: 約7 credits
- `GPT-5.3-Codex`: 約5 credits
- `GPT-5.1-Codex-mini`: 約1 credit

さらに同じヘルプでは、**クレジット消費はモデル、タスクサイズ、複雑さ、必要な reasoning によって変わる** と説明されています。

そして GPT-5.4 のモデルページでは、`reasoning.effort` に `low / medium / high / xhigh` があると案内されています。

ここから言えるのは、少なくとも **GPT-5.4 自体が軽量モデルではなく、しかも `xhigh` 側を使っていれば、消費が速く見えても不思議ではない** ということです。

これは公式の数値からの推測ですが、私の体感ともかなり一致していました。

## つまり、こういう組み合わせはかなり重い

以下が重なると、残量の減りが急に早く見えます。

- `GPT-5.4`
- `reasoning.effort = xhigh`
- `Fast mode` ON
- 長いセッション
- 大きいコードベース

特に `Fast mode` はそれ単体で説明がつくレベルで効きます。  
そこに `GPT-5.4 extra high` が重なると、「少し触っただけのつもりなのに残量が減る」という感覚になりやすいです。

## 今ならこう切り分ける

もし同じ症状が出たら、私は次の順で見ます。

1. OpenAI Status に使用量関連の障害が出ていないか確認する
2. `Fast mode` がONか確認する
3. モデルが `GPT-5.4` になっていないか確認する
4. `reasoning.effort` が `xhigh` になっていないか確認する
5. 新しいセッションで軽い条件に落として比較する

比較するときは、まず以下の軽い条件を作ると差が分かりやすいです。

- `Fast mode` なし
- 可能なら軽いモデル
- 長い会話の続きではなく新規セッション
- リポジトリ全体ではなく対象を絞る

## まとめ

「ほとんど使っていないのに4%になった」という最初の違和感には、たしかにサービス側の障害要因も絡んでいました。

ただ、後から見ると、**自分で `GPT-5.4 extra high` と `Fast mode` という重い条件を踏んでいた** のもかなり大きかったです。

特に今回のオチはシンプルで、

- 障害は実際にあった
- でも自分の設定も重かった
- `Fast mode` は公式に2倍消費

この3点です。

なので、Codexの残量が妙に速く減るときは、「障害かも」で止まらず、**モデル・推論強度・Fast mode** までセットで見たほうが早いです。

## 参考リンク

- OpenAI Status: Issues with Increased Codex Usage Rate  
  https://status.openai.com/incidents/01KK26XE1W536H7DQV2EXM3GHE
- OpenAI Codex Docs: Speed  
  https://developers.openai.com/codex/speed
- OpenAI Help Center: Codex rate card  
  https://help.openai.com/en/articles/20001106-codex-rate-card
- OpenAI API Docs: GPT-5.4  
  https://developers.openai.com/api/docs/models/gpt-5.4
