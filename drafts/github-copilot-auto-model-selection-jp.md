---
title: "GitHub Copilot の「Auto」モデル選択が地味に革命だった話 — 無料枠を使い切って課金している今だからこそ、刺さる"
slug: "github-copilot-auto-model-selection-jp"
status: "publish"
categories: 
  - "AI"
tags: 
  - "GitHub Copilot"
  - "VS Code"
  - "AI"
  - "生産性"
  - "コスト最適化"
featured_image: "../images/image copy 16.png"
---

# GitHub Copilot の「Auto」モデル選択が地味に革命だった話 — 無料枠を使い切って課金している今だからこそ、刺さる

![Claude Sonnet 4.5が0.9x（10%割引）で表示されている](../images/image%20copy%2016.png)

最近、VS Code の Copilot に **「Auto」モデル選択**が追加された。
正直、最初は「また便利そうに見せた設定が増えただけでしょ」と思っていた。

でも、実際に仕様を調べて、今の自分の使い方に当てはめてみると、
これはかなり**実務に効くアップデート**だった。

## 無料枠を使い切ったあとに見える景色

自分はすでに Copilot の無料枠を使い切って、現在は課金して使っている。
つまり、

- 「どのモデルを使うか」
- 「これ、高いモデルを使うべき処理か？」
- 「今のやりとり、無駄にコスト食ってないか？」

みたいなことが、**常に頭のどこかに引っかかっていた**。

これ、地味だけど確実に**思考コストを食う**。

![VS Codeでモデル選択している画面](../images/image%20copy%2017.png)

## Auto がやっていることは、実はかなり本質的

[公式ドキュメント](https://docs.github.com/en/copilot/concepts/auto-model-selection)を読むと、Auto の正体はシンプルだ。

- 利用可能（契約・ポリシー的に許可）なモデルの中だけで
- タスク内容に応じて最適なモデルを自動選択
- しかも **premium request の消費が 10% 割引（0.9x）**

つまり、

> 「高いモデルを常にフル回転させる」
> から
> 「必要なときだけ高性能モデルを使う」

に、**強制的に最適化される**。

これ、冷静に考えるとかなり強い。

## 何が一番アツいかというと

価格の 10% 割引そのものも嬉しいけど、
本当にデカいのはそこじゃない。

**「モデル選択を考えなくていい」**
これに尽きる。

- 今の問いは重いのか軽いのか
- どのモデルが妥当か
- コスパ的にどうか

こういう判断を、**毎回人間がやる必要がなくなった**。

これは、

- CPU のクロックを意識しなくなった
- メモリ管理を手動でやらなくなった

のと同じ種類の進化だと思う。

## 課金ユーザーほど恩恵が大きい

無料枠で使っている間は、正直この価値は見えにくい。
でも、

- 無料枠を使い切った
- 日常的に Copilot を仕事で使っている
- コードも文章も調査も全部投げている

こういう状態になると、
**「頭のリソースをどこに使うか」**がめちゃくちゃ重要になる。

その観点で見ると、Auto は

- 精度を落とさず
- コストを抑え
- 思考コストをほぼゼロにする

かなり完成度の高い仕組みだ。

## Auto の仕様（公式情報まとめ）

公式ドキュメントによると、以下の点が保証されている：

1. **許可されたモデルのみ使用**
   - 契約やポリシーで無効化されているモデルは選ばれない
   - どのモデルが選ばれたかは、チャット画面でホバーすると確認可能

2. **10%割引の仕組み**
   - premium request の multiplier に対して 10% 割引が適用される
   - 例：通常 1x のモデル → Auto 使用時は 0.9x としてカウント
   - Claude Sonnet 4.5 の場合：通常 1x → Auto で 0.9x

3. **GA（正式提供）済み**
   - プレビュー機能から正式リリースされたため、挙動は安定している
   - [2024年12月10日に正式リリース](https://github.blog/changelog/2025-12-10-auto-model-selection-is-generally-available-in-github-copilot-in-visual-studio-code/)

## 結論

今のところ、

- 普段使い → **Auto 一択**
- 明確に理由があるときだけ → 手動でモデル指定

これが一番バランスがいい。

モデルを選ぶのはもう人間の仕事じゃない。
人間がやるべきなのは、**問いをちゃんと立てること**。

Copilot の Auto は、その役割分担を一段先に進めた機能だと思う。

---

**参考リンク：**
- [GitHub Copilot: Auto model selection (公式ドキュメント)](https://docs.github.com/en/copilot/concepts/auto-model-selection)
- [Auto model selection is generally available (GitHub Blog)](https://github.blog/changelog/2025-12-10-auto-model-selection-is-generally-available-in-github-copilot-in-visual-studio-code/)
