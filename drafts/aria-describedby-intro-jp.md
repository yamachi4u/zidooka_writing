---
title: 【WCAG】alt属性で説明しきれない？aria-describedbyで「説明の避難先」を作る
date: 2026-02-01 09:00:00
categories: 
  - アクセシビリティ
slug: aria-describedby-intro-jp
tags: 
  - WCAG
  - ARIA
  - HTML
  - Accessibility
status: publish
---

WCAGやアクセシビリティ対応を意識し始めると、必ずぶつかる壁があります。

「alt属性（代替テキスト）は重要だ。画像には意味を書くべきだ」

ここまでは分かります。しかし、実装を進めていると、ふと手が止まる瞬間が訪れます。

「画像の意味はこれで合ってる。でも、説明したい情報が多すぎる……」
「これ全部 alt に書くの……？ なんか違和感ない……？」

もしあなたがそう感じたことがあるなら、その感覚は**かなり健全**です。
そしてその違和感の正体は、「altに詰め込みすぎている」ことへの警告です。

今回は、alt属性だけでは解決できない「複雑な説明」をどう扱うべきか、**aria-describedby** という解決策を使って解説します。

## その違和感の正体は「情報量」ではない

多くの人がここで勘違いします。

「altが長くなるのがダメなんだ。もっと短くしなきゃ」

しかし、問題の本質は「長さ」ではありません。
本当の問題は、**「役割の違う情報を、altという1つの箱に詰め込もうとしている」** ことにあります。

### altの役割をリセットする

ここで一度、altの役割を割り切って考えましょう。
altが担うのは、基本的にこれだけです。

**「これは何か」を短く伝える**

例えば：

```html
<img src="chart.png" alt="売上推移のグラフ">
```

これで十分です。
「数値の意味」「増減の理由」「注意点」などは、本来altの仕事ではありません。

### やりがちなNG例

「説明」をどこに書くか迷った結果、多くの人がやってしまうのがこれです。

```html
<!-- ❌ NG例：altに全部詰め込んでいる -->
<img
  src="chart.png"
  alt="売上推移のグラフ 2023年から2024年にかけて右肩上がりで 特に第3四半期に大きく伸びている"
/>
```

真面目に取り組んでいるからこそ陥る罠ですが、これは構造として破綻しています。
altに「名前」「説明」「分析」を全部入れてしまっているからです。

## そこで登場するのが「ARIA」

ここでようやく **ARIA (Accessible Rich Internet Applications)** の出番です。

ARIAは、HTMLだけでは表現しきれない「意味・役割・関係性」を補うための仕組みです。
ざっくり言えば、「見た目では分かるけど、HTMLだけだと分からない」情報を機械に教えるための属性群です。

### aria-describedby とは？

`aria-describedby` は、その中でもかなり分かりやすい役割を持っています。

**「この要素の説明は、ここに書いてありますよ」と紐づけるための属性**

ポイントはこれです。
*   説明文を新しく書く属性ではない
*   **既にある説明文を関連づける属性**

### altで限界を感じた例を、正しく分解する

先ほどの例を、役割分担して書き直してみましょう。

```html
<!-- ✅ OK例：役割を分ける -->
<img
  src="plan.png"
  alt="料金プランの図"
  aria-describedby="plan-desc"
/>

<!-- 説明は本文として書く -->
<p id="plan-desc">
  ベーシックプランは月980円で広告あり。
  プレミアムプランは月1980円で広告なし、家族共有が可能です。
</p>
```

ここで起きていることは以下の通りです。

1.  **alt**：「これは料金プランの図です」と名前を伝える。
2.  **本文**：詳しい説明は `<p>` タグなどで普通に書く。
3.  **aria-describedby**：「この `<p>` は、あの画像の説明です」と紐づける。

情報量は減っていません。**置き場所を分けただけ**です。
スクリーンリーダーは「画像、料金プランの図。説明：ベーシックプランは……」といった形で、名前と説明を段階的に読み上げることができます。

## 「aria-describedbyを使うべきか？」の判断軸

ZIDOOKA！的に、判断基準はシンプルです。

**altに書くと「説明っぽく」なり始めたら、aria-describedbyを考える**

具体的には、以下のような情報はaltに無理やり入れず、別の場所（本文や注釈）に書いて紐づけることを検討してください。

*   条件・前提
*   注意事項
*   入力ルール（フォームの場合）
*   補足説明
*   エラーメッセージの詳細

## WCAGは「ariaを使え」と言っているわけではない

誤解されがちですが、WCAG（Web Content Accessibility Guidelines）は「ariaを使え」「altは短くしろ」と直接言っているわけではありません。

言っているのは、**「情報は、役割に応じて構造的に提供しなさい」** という原則です。
`aria-describedby` は、その原則をHTMLで実装するための道具の一つにすぎません。

## まとめ：altで違和感を覚えたら、それは「構造化」のチャンス

altを書いていて「これ全部書くの変だな」と思ったなら、それは知識不足ではありません。
**構造を意識し始めたサイン**です。

その違和感の答えが、`aria-describedby` や `aria-labelledby`、そしてWCAGの考え方につながっています。
「altに全部詰め込む」のをやめて、「説明の避難先」を作ってあげましょう。

---

## 参考リンク（公式・一次情報）

altで止まってしまった人が「答え合わせ」に行けるサイトをまとめました。

### WCAG（基準そのもの）
*   [WCAG 2.2 Recommendation](https://www.w3.org/TR/WCAG22/)
    *   「何を満たせばよいか」の正式基準。
*   [1.1.1 Non-text Content](https://www.w3.org/TR/WCAG22/#non-text-content)
    *   altで何を求めているのかの原点。「全部altに書け」とは言っていないことが読み取れます。

### ARIA（仕様とガイド）
*   [WAI-ARIA 1.2 Specification - aria-describedby](https://www.w3.org/TR/wai-aria-1.2/#aria-describedby)
    *   「説明を関連付ける」という役割が明文化されています。
*   [ARIA Authoring Practices Guide (APG) - Names and Descriptions](https://www.w3.org/WAI/ARIA/apg/practices/names-and-descriptions/)
    *   **超重要**。「名前 (Name)」と「説明 (Description)」の違いを体系的に説明している神ページです。

### 実装ガイド
*   [WAI Tutorials - Images Concepts](https://www.w3.org/WAI/tutorials/images/)
    *   altだけで済まないケースを丁寧に分解しています。
*   [MDN - aria-describedby](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Attributes/aria-describedby)
    *   実装例が分かりやすいです。
