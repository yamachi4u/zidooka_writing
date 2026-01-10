---
title: "【脱・手作業】AIに考えさせた「最強のアンケート」を、一瞬でGoogleフォームにするGAS術"
date: 2025-02-11 09:00:00
categories:
  - GAStips
tags:
  - GAS
  - ChatGPT
  - AI
  - Google Forms
  - 業務効率化
status: publish
slug: ai-gas-form-generation-jp
featured_image: ../images/2025/thumbnails.png
---

Googleフォームを作るとき、まだ画面で「＋」ボタンをポチポチ押して、質問文を手打ちしていませんか？

その作業、もう**AIとGAS（Google Apps Script）に任せましょう**。

1. **AI (ChatGPT/Claude) に「いい感じのアンケート項目」を考えさせる**
2. **AIに「それをJSONデータにして」と頼む**
3. **GASにコピペして実行する**

これだけで、複雑な分岐や大量の質問があるフォームも**一瞬で完成**します。
「フォームを作る」という作業は、もはや人間がやる仕事ではありません。

この記事では、**AIが出力したJSONをそのままGoogleフォームに変換する「魔法のGASコード」**を公開します。

---

## ステップ1：AIに構成を考えさせる

まず、ChatGPTやClaudeにこう命令します。

> 「エンジニア採用の応募フォームを作りたいです。必要な項目を網羅して。
> その構成を、以下のJSONフォーマットに合わせて出力してください」

```json
{
  "title": "フォームのタイトル",
  "description": "説明文",
  "sections": [
    {
      "title": "セクション名",
      "items": [
        { "type": "text", "title": "質問文", "required": true },
        { "type": "multipleChoice", "title": "選択式の質問", "choices": ["A", "B"] }
      ]
    }
  ]
}
```

するとAIは、採用のプロ顔負けの質問項目を考え出し、それを**プログラムで扱えるJSONデータ**として返してくれます。

## ステップ2：GASにコピペして実行

AIが作ってくれたJSONを、以下のGASコードの `FORM_SCHEMA` 部分に貼り付けるだけです。

### コピペ用GASコード

```javascript
// ▼ここにAIが作ったJSONを貼り付けるだけ！
const FORM_SCHEMA = {
  title: "AIが考えた最強の採用フォーム",
  description: "ChatGPTにより自動生成されたフォーム構成です。",
  sections: [
    {
      title: "基本情報",
      items: [
        { type: "text", title: "氏名", required: true },
        { type: "text", title: "メールアドレス", required: true },
        { type: "multipleChoice", title: "希望職種", choices: ["フロントエンド", "バックエンド", "SRE", "PM"] }
      ]
    },
    {
      title: "スキル・経験",
      items: [
        { type: "checkbox", title: "経験のある技術スタック", choices: ["React", "Vue", "Next.js", "Node.js", "Go", "Python", "AWS", "GCP"] },
        { type: "text", title: "ポートフォリオURL" },
        { type: "multipleChoice", title: "実務経験年数", choices: ["1年未満", "1-3年", "3-5年", "5年以上"] }
      ]
    }
  ]
};

// ▼ここから下はいじる必要なし（自動生成ロジック）
function createFormFromSchema(schema) {
  const form = FormApp.create(schema.title);
  if (schema.description) form.setDescription(schema.description);

  schema.sections.forEach(section => {
    form.addSectionHeaderItem().setTitle(section.title);
    section.items.forEach(item => addItem(form, item));
  });

  Logger.log('✅ フォーム作成完了！');
  Logger.log('編集用URL: ' + form.getEditUrl());
  Logger.log('回答用URL: ' + form.getPublishedUrl());
}

function addItem(form, item) {
  let q;
  switch (item.type) {
    case 'text':
      q = form.addTextItem();
      break;
    case 'paragraph':
      q = form.addParagraphTextItem();
      break;
    case 'multipleChoice':
      q = form.addMultipleChoiceItem();
      q.setChoices(item.choices.map(c => q.createChoice(c)));
      break;
    case 'checkbox':
      q = form.addCheckboxItem();
      q.setChoices(item.choices.map(c => q.createChoice(c)));
      break;
    case 'dropdown':
      q = form.addListItem();
      q.setChoices(item.choices.map(c => q.createChoice(c)));
      break;
    case 'date':
      q = form.addDateItem();
      break;
    default:
      return; // 未対応のタイプはスキップ
  }

  q.setTitle(item.title);
  if (item.help) q.setHelpText(item.help);
  if (item.required) q.setRequired(true);
  if (item.other && (item.type === 'multipleChoice' || item.type === 'checkbox')) {
    q.setHasOtherOption(true);
  }
}

function run() {
  createFormFromSchema(FORM_SCHEMA);
}
```

## なぜこれが「革命」なのか？

### 1. 修正が「会話」で終わる
「選択肢に『その他』を追加して」「やっぱり経験年数の刻みを変えて」
これらをGUIでポチポチ直すのは面倒です。AIに「JSONを修正して」と言えば、一瞬で新しい定義データが出てきます。それを貼り直して再実行すれば、修正版フォームの完成です。

### 2. フォームを「コード」で管理できる
作成したJSONを保存しておけば、「先月のイベントで使ったあのフォーム、もう一回作りたい」という時も一瞬で復元できます。Gitでバージョン管理さえ可能です。

### 3. 誰でも「プロ級」のフォームが作れる
アンケート設計のノウハウがなくても、AIに「マーケティングのプロとして、顧客満足度を深掘りする質問を考えて」と指示すれば、質の高い設問が手に入ります。それをそのまま実装できるのです。

## まとめ

Googleフォーム作成は、もはや手作業ではありません。

**「AIに構成を考えさせ、GASで実体化する」**

このフローを手に入れると、アンケート、申し込み、問い合わせ対応のスピード感が劇的に変わります。ぜひ、上記のコードをコピペして試してみてください。