---
title: "DeepSeek V4 Flash 0731とGPT-5.6 Luna、同じ仕事を任せたら料金はどれくらい違う？コスト感覚を比べてみた"
categories:
  - AI
tags:
  - DeepSeek
  - DeepSeek V4
  - GPT-5.6
  - Luna
  - OpenAI
  - 生成AI
  - 比較
  - 料金
  - サブスク
  - API
  - OpenCode
status: publish
slug: deepseek-v4-flash-0731-vs-gpt-5-6-luna-cost-jp
featured_image: ../images/2026/deepseek-v4-flash-vs-luna-cost.png
---

2026年7月、両横綱がそろって新モデルを出した。DeepSeekは7月31日に **DeepSeek V4 Flash 0731** を、OpenAIは7月9日に **GPT-5.6** ファミリーを公開した。今回は両者の「最安モデル」同士を比べる。GPT-5.6 LunaとDeepSeek V4 Flash 0731は、同じ仕事を任せたら料金でどれくらい変わるのか。

結論から言うと、同じ作業量ならDeepSeekのほうが **Lunaの3分の1〜4分の1** で済む。サブスクで使う場合は「ほぼ使い放題」の感覚になる。この記事では料金表と実際のコスト感覚を順に説明していく。

## GPT-5.6 Lunaって何？

GPT-5.6は1つのモデルじゃなくて、Sol・Terra・Lunaの3段構成で公開された。Lunaはその中で一番速くて一番安いモデルで、ざっくり言うと「nano級」の位置づけ。1,050,000トークンのコンテキストを持ち、テキストと画像の入力に対応する。

公式APIの料金は次の通り。単位はすべて100万トークンあたりだ。

| モデル | 入力 | キャッシュ入力 | 出力 |
|---|---|---|---|
| GPT-5.6 Luna | $0.2 | $0.02 | $1.2 |
| GPT-5.6 Terra | $2 | $0.2 | $12 |

参考に、TerraはLunaの10倍の値段。Sol（最上位）はさらに高い。

## DeepSeek V4 Flash 0731の料金

DeepSeek V4 Flash 0731は1Mトークンのコンテキスト、thinking／non-thinkingの両モード、ツール呼び出しに対応する。総パラメータ284B、推論時のアクティブパラメータは13Bだ。

料金は利用経路によって変わる。

| 経路 | 入力 | 出力 |
|---|---|---|
| DeepSeek公式API | $0.14（キャッシュヒット時$0.0028） | $0.28 |
| OpenRouter | $0.09 | $0.18 |

:::note
DeepSeek公式のキャッシュヒット料金は$0.0028と、ほぼタダに近い。同じ長いシステムプロンプトを何度も送る定型作業なら、ここが大きく効いてくる。
:::

## 同じ仕事をやらせたら実際いくらか

単純比較だと数字が大きくてピンと来ない。1回の作業を「入力10,000トークン＋出力2,000トークン」と仮定して計算してみる。

| モデル | 1回の作業 | 月100回 |
|---|---|---|
| DeepSeek公式API | 約$0.002（約0.3円） | 約$0.2（約30円） |
| GPT-5.6 Luna | 約$0.004（約0.7円） | 約$0.4（約70円） |

出力が多い作業ほど差が開く。出力トークンの単価がLunaは$1.2、DeepSeekは$0.28だから、出力中心の仕事だと **4分の1以下** になる。

:::example
実際に私がDeepSeek V4 Flashで1つの会話を終えたとき、利用額は$0.01だった。円にすると数円。これを「1回の作業」のイメージにすると、コスト感覚が掴みやすい。
:::

## サブスクで使うと「ほぼ使い放題」

APIは従量課金だから、使う量が増えるとそのぶん料金も増える。毎日エージェントとして使うなら、サブスクのほうが感覚がいい。

DeepSeek V4 Flash 0731はOpenCode Go（初月$5、その後月$10）で使える。OpenCodeの公式ドキュメントでは、典型的な利用パターンで **月158,150リクエスト相当** まで想定されている。これは保証回数ではなくトークン量による推定値だけど、個人の作業量ならまず枯渇しない。

ChatGPTのサブスク（Plusは月$20）と比べると、月額は半分になる。そのうえ「メッセージ上限を気にせず使える」感覚に変わるのが大きい。

:::conclusion
選ぶ基準はシンプルだ。**月$10分（約1,500円）をAPIで超えて使うかどうか**。超えるならOpenCode Goのサブスク、超えないなら公式API。軽く試すならOpenRouterでもいい。コストだけでなく、入力するデータの種類と機能差も合わせて選ぶのが安全だ。
:::

## 注意点

料金だけ見て乗り換えるのはまだ早い。気をつけたい点が3つある。

- **プライバシー**: OpenCode Goのプライバシー表では、DeepSeek V4 Flashは「モデル学習に使用」「データ保持期間の合意なし」と表示されている。顧客情報、APIキー、未公開の契約書を送る用途には向かない
- **機能差**: Lunaは画像入力、Web検索、コードインタープリターなど多彩なツールに対応する。DeepSeekもResponses APIやツール呼び出しに対応するけど、対応機能の範囲は異なる。画像を扱う仕事ならLuna側が楽だ
- **質の差**: DeepSeekはコードエージェント向けのベンチマークが強く、Terminal Bench 2.1で82.7を出している。ただ、ベンチマークは特定環境で測った数字なので、自分の用途での体感で判断するのがおすすめ

## まとめ

DeepSeek V4 Flash 0731は、同じ仕事をGPT-5.6 Lunaに任せた場合の3分の1〜4分の1の料金で動く。サブスクならさらに「ほぼ使い放題」だ。ただしプライバシーと機能の違いは、料金の安さとトレードオフになっている。そこだけ確認してから乗り換えを決めるのがよさそうだ。

前回の記事『[DeepSeek V4 Flash 0731を便利に使う4つの方法](https://www.zidooka.com/archives/4596)』では、公式API・OpenCode Go・OpenRouter・セルフホストの使い分けを詳しくまとめている。

## 参考リンク

- [DeepSeek API：2026年7月31日のV4 Flash更新](https://api-docs.deepseek.com/updates/)
- [DeepSeek API：モデルと料金](https://api-docs.deepseek.com/quick_start/pricing/)
- [OpenCode Go：料金・モデル・プライバシー](https://dev.opencode.ai/docs/de/go/)
- [OpenAI：GPT-5.6について](https://openai.com/index/gpt-5-6/)
- [OpenAI API：GPT-5.6 Lunaモデル](https://developers.openai.com/api/docs/models/gpt-5.6-luna)
- [OpenRouter：DeepSeek V4 Flash 0731](https://openrouter.ai/deepseek/deepseek-v4-flash-0731)

*本記事の情報と料金は2026年8月4日時点の確認内容です。サービスの料金、利用枠、モデル提供状況は変更される可能性があります。*
