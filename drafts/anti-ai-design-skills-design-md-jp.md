---
title: "AIっぽくないデザインをつくるためのSKILL.md／DESIGN.md集"
categories:
  - WEB制作
tags:
  - AI開発
  - フロントエンド
  - Webデザイン
  - Claude Code
  - Codex
  - DESIGN.md
  - SKILL.md
status: publish
slug: anti-ai-design-skills-design-md
---

「AIにWebサイトを作らせると、なぜか似たような見た目になる」という問題があります。紫や青のグラデーション、Inter系のサンセリフ、角丸カードの3列グリッド、薄い影、中央揃えのヒーロー。動くけれど、どこかで見たことのある画面です。

この現象は、AIがデザインできないというより、指示がないと「平均的で安全なデザイン」に収束するために起こります。対策として有効なのが、デザインの判断基準をSKILL.mdやDESIGN.mdに書いて、エージェントに毎回読ませる方法です。

:::conclusion
AIっぽさを減らす鍵は、「いい感じにして」と頼むことではありません。避けたい既視感、選ぶべき美学、タイポグラフィ、色、余白、コンポーネントのルールを、リポジトリに保存することです。
:::

## まず読むべきSKILL.md

### Anthropic／frontend-design

[Anthropicのfrontend-design](https://github.com/anthropics/claude-code/tree/main/plugins/frontend-design)は、特徴的で意図のあるUIをつくるための基本スキルです。実装前に、目的・利用者・トーン・技術的制約・記憶に残る一点を決めるよう促します。

「ミニマル」「高級」「ブルータリスト」「エディトリアル」など、方向性を明確に選び、フォント、配色、空間構成、モーションまで一貫させるのがポイントです。

### Nutlope／hallmark

[Nutlope/hallmark](https://github.com/Nutlope/hallmark)は、AIが生成しがちな画面を監査・再設計するためのスキルです。単に「おしゃれにする」のではなく、画面に明確な美学を与え、複数の観点から既視感をチェックします。

導入例：

```bash
npx skills add https://github.com/Nutlope/hallmark --skill hallmark
```

使い方は、最初から使うなら`build`、既存画面を点検するなら`audit`、作り直すなら`redesign`という整理がわかりやすいです。

### pbakaus／impeccable

[impeccable](https://github.com/pbakaus/impeccable)は、ブランド向けの画面とプロダクト向けの画面を区別しながら、既存UIを改善するスキルです。新規制作だけでなく、タイポグラフィ、色、余白、密度、アクセシビリティを段階的に見直したいときに向いています。

「もっと派手に」だけでなく、「静かにする」「読みやすくする」「密度を調整する」といった修正にも使える点が実用的です。

### Vercel／Web Interface Guidelines

[VercelのWeb Interface Guidelines](https://github.com/vercel-labs/agent-skills/tree/main/skills/web-design-guidelines)は、見た目の個性そのものよりも、操作性・アクセシビリティ・レスポンシブ対応・フォーム・キーボード操作などを確認するためのチェックリストです。

AIっぽくないデザインは、奇抜であることと同義ではありません。個性的な方向性を選んだうえで、使いやすさを壊さないために、この種のガイドラインを最後の監査に使うのがよいです。

## DESIGN.mdに何を書くか

SKILL.mdが「エージェントの振る舞い」を定めるものだとすれば、DESIGN.mdは「このプロジェクトの記憶」です。

[Googleのdesign.md](https://github.com/google-labs-code/design.md)の考え方を参考に、次のような項目を固定します。

- 画面の目的と想定ユーザー
- デザインの言葉：たとえば「紙の編集部」「無機質な作業台」「古い機械の操作盤」
- 使用するフォントと役割
- 背景、本文、強調色、警告色
- 余白、角丸、境界線、影のトークン
- ボタン、入力欄、カード、ナビゲーションの原則
- 絶対に避ける表現
- 画像・アイコン・モーションの扱い
- アクセシビリティ上の最低条件

たとえば、次のような短いファイルでも効果があります。

```md
# DESIGN.md

## Direction
印刷物のような編集的デザイン。SaaSダッシュボードにはしない。

## Avoid
- 紫から青へのグラデーション
- Inter、Roboto、Arialの機械的な組み合わせ
- 何でもカードにするレイアウト
- 意味のないぼかし、発光、浮遊アニメーション

## Tokens
- Background: warm paper
- Text: near black
- Accent: vermilion
- Radius: 2px〜6px
- Border: 1px solid, shadowは原則使わない

## Review
実装後に、既視感・階層・タイポグラフィ・密度・操作性を監査する。
```

重要なのは、色コードを並べるだけではなく、「なぜそのデザインなのか」と「何をしないのか」を書くことです。

## 実際の導入手順

:::step
1. `frontend-design`または`hallmark`を導入する。
2. リポジトリのルートに`DESIGN.md`を作る。
3. 生成前に、方向性を一つ選ぶ。
4. 「AIっぽくしない」ではなく、避ける具体例を書く。
5. 実装後に`audit`とアクセシビリティのチェックを実行する。
6. 人間が最終的に、余白・文字組み・密度・細部を確認する。
:::

プロンプトも、次のように変えると結果が安定します。

:::example
「AIっぽくないサイトを作って」ではなく、「地方の小さな印刷所がつくった作業台のようなサイト。紙の白ではなく少し黄味のある背景。見出しは幅のあるセリフ体、本文は読みやすいゴシック。カードを多用せず、罫線と余白で階層をつくる。紫グラデーション、Inter、発光、巨大な中央揃えヒーローは禁止」と指定します。
:::

## ただし、スキルだけでは足りない

スキルは「中央値から離れるための初期条件」を与えます。しかし、どの方向に離れるかは決めてくれません。編集的なのか、工業的なのか、遊び心があるのか。そこはプロジェクトの目的と、つくる人の趣味から決める必要があります。

:::warning
「AIっぽくない」を目的にしすぎると、今度は奇抜さだけが残ります。個性は、色を派手にすることではなく、目的に合った一貫した判断を積み重ねた結果です。
:::

## まとめ

AIにデザインを任せるとき、最も大切なのは「センスを出して」と頼むことではなく、センスの判断材料を外部化することです。

- `SKILL.md`で制作・監査の手順を与える
- `DESIGN.md`でプロジェクト固有の美学を保存する
- 避けるべきAI定番を具体的に書く
- 目的に合う方向性を一つ選ぶ
- 最後はアクセシビリティと人間の目で確認する

:::conclusion
AIはデザインの中央値を出すのが得意です。だからこそ、中央値からどの方向へ、なぜ離れるのかをMDファイルに書いておくと、生成物は一気に「そのプロジェクトのもの」になります。
:::

## 参考リンク

- [Anthropic frontend-design](https://github.com/anthropics/claude-code/tree/main/plugins/frontend-design)
- [Nutlope/hallmark](https://github.com/Nutlope/hallmark)
- [pbakaus/impeccable](https://github.com/pbakaus/impeccable)
- [Vercel Web Interface Guidelines](https://github.com/vercel-labs/agent-skills/tree/main/skills/web-design-guidelines)
- [Google design.md](https://github.com/google-labs-code/design.md)
- [awesome-design-skills](https://github.com/bergside/awesome-design-skills)
