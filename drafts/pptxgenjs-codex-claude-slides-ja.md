---
title: "PptxGenJSがかなり強い。CodexやClaudeでPowerPointを自動生成するなら有力候補です"
slug: "pptxgenjs-codex-claude-slides-ja"
status: "draft"
categories:
  - "AI"
  - "Tools"
tags:
  - "PptxGenJS"
  - "Codex"
  - "Claude"
  - "PowerPoint"
  - "Node.js"
featured_image: ../images/2026/04/pptxgenjs-demo/slide-01-title.png
---

PptxGenJS を見ていて、これはかなり実務向きだと感じました。JavaScript から PowerPoint を組み立てられる系のライブラリはいくつかありますが、**「とりあえずスライドを 1 枚作る」だけで終わらず、実際の業務資料に寄せやすい**のが強いです。

特に、Codex や Claude のようなコード生成系のエージェントと相性がいいです。雑な箇条書き、表データ、数値を渡して、Node.js スクリプトまで作らせれば、そのまま `.pptx` まで落とし込めます。

:::conclusion
PptxGenJS は「AI に資料の本文を書かせる」より一段進んで、「PowerPoint の構造そのものをコードで量産・再利用したい」場面でかなり強いです。
:::

## 何がそんなに良いのか

PptxGenJS の公式サイトでは、Node.js、React、ブラウザなど複数環境で動き、テキスト、表、図形、画像、グラフを扱えると案内されています。さらに、Slide Master、HTML テーブルからのスライド生成、ブラウザでの `.pptx` 出力まで揃っています。

つまり、単なる「PowerPoint を書き出せるライブラリ」ではなく、**テンプレ資料のエンジン**として使いやすいです。

:::note
AI エージェントに任せるなら、レイアウトを自然言語で毎回説明するより、PptxGenJS のテンプレを 1 本持っておくほうが安定します。
:::

## こういうスライドを自動生成しやすいです

今回の作業では、`pptxgenjs` をローカルに追加して、Codex / Claude 向けの紹介デモを Node.js から生成できる形にしました。下の画像は、そのサンプル deck の見た目を把握しやすいように置いたプレビューです。

![PptxGenJS demo title slide](../images/2026/04/pptxgenjs-demo/slide-01-title.png)

1 枚目はタイトルスライドです。色、余白、帯、ラベルを固定しておけば、AI に本文だけ差し替えさせる運用がしやすくなります。

![PptxGenJS bilingual brief slide](../images/2026/04/pptxgenjs-demo/slide-02-bilingual.png)

2 枚目は日英のバイリンガル要約です。**日本語カラムと英語カラムを同時に出す**ようなレイアウトは、人間が毎回整えると地味に面倒ですが、コード化すると再利用がかなり楽です。

![PptxGenJS metrics slide](../images/2026/04/pptxgenjs-demo/slide-03-chart.png)

3 枚目は数値サマリーです。グラフや補足カードが入ると、もう「テキストを並べるだけの自動化」ではなく、**ちゃんと資料として読める形**に近づきます。

## Codex や Claude にやらせるなら、たしかにこれです

ここが本題です。

Codex や Claude に「プレゼン資料を作って」と言うだけだと、たいていは本文や見出し案までは出せても、PowerPoint そのものの仕上がりは不安定です。ですが、PptxGenJS のテンプレと制約を先に用意しておけば、エージェント側の仕事はかなり整理できます。

たとえば、次のような分担にできます。

1. AI に構成案を出させる
2. AI に各スライドの見出し、箇条書き、表データを JSON で出させる
3. その JSON を PptxGenJS に流して `.pptx` を生成する

:::step
「資料の内容を考える部分」と「PowerPoint の見た目を崩さず出力する部分」を分離すると、AI エージェント運用はかなり安定します。
:::

これは、週報、営業資料、経営会議メモ、進捗報告、イベントの概要資料などで特に効きます。毎回ゼロからデザインさせるより、**スライド骨格をコードで固定したほうが事故が少ない**からです。

## 公式機能を見ると、実務寄りです

PptxGenJS の公式ドキュメントを見ると、次の機能が特に実務向きです。

- テキスト、表、図形、画像、グラフの生成
- Slide Master による共通デザイン化
- HTML テーブルを複数スライドへ流し込む `tableToSlides()`
- ブラウザでも Node.js でも `.pptx` を出力できる構成
- Node.js、React、Vite、Electron、Serverless での利用

:::example
管理画面の一覧表や BI のテーブルを HTML で持っているなら、`tableToSlides()` を経由して PowerPoint 化する流れはかなり実用的です。
:::

## 実際に AI へ投げるときの依頼イメージ

エージェントに丸投げするより、入力を少し構造化したほうがうまくいきます。

```text
次の JSON から 16:9 の PowerPoint を PptxGenJS で生成してください。
- 3 slides
- タイトルは日本語、補足は英語
- 色はティール系
- Slide 2 は日英の2カラム
- Slide 3 は棒グラフと要点カード
- 出力は .pptx
```

このようにレイアウト条件まで渡しておくと、Codex でも Claude でもコード生成のブレが減ります。

## 注意点もあります

もちろん万能ではありません。

:::warning
PptxGenJS を入れたからといって、AI がいきなり美しい資料を毎回作るわけではありません。テンプレ、色、余白、フォント、部品サイズの基準は人間側で先に決めたほうが安定します。
:::

また、図版の作り込みやアニメーション演出まで強く求めると、別の調整が必要です。ただ、**定型資料を量産したい**という目的なら、かなり筋が良いです。

## まとめ

PptxGenJS は、PowerPoint を JavaScript で触るためのライブラリというより、**AI 時代の資料テンプレ基盤**として見ると価値がわかりやすいです。

Codex や Claude に「資料を作らせたい」と思ったとき、本文生成だけで止めず、PptxGenJS を挟んで `.pptx` まで落とすと実務に乗せやすくなります。

「AI に PowerPoint を作らせるなら何を軸にするか」で迷っているなら、かなり有力候補です。

## References

1. PptxGenJS Home
https://gitbrent.github.io/PptxGenJS/
2. PptxGenJS Introduction
https://gitbrent.github.io/PptxGenJS/docs/introduction/
3. PptxGenJS HTML to PowerPoint
https://gitbrent.github.io/PptxGenJS/docs/html-to-powerpoint/
4. PptxGenJS Masters and Placeholders
https://gitbrent.github.io/PptxGenJS/docs/masters.html
5. PptxGenJS Charts
https://gitbrent.github.io/PptxGenJS/docs/api-charts.html
