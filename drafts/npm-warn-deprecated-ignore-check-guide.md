---
title: "【npm】「npm warn deprecated」が出た！無視していい警告とヤバい警告の見分け方"
slug: npm-warn-deprecated-ignore-check-guide
date: 2026-01-13 23:55:00
categories: 
  - general
  - エラーについて
  - 便利ツール
tags: 
  - npm
  - node.js
  - warning
  - deprecated
status: publish
featured_image: ../images/npm-warn-deprecated.png
---

npm install を実行したら、ターミナルが真っ赤（または黄色）になって驚いたことはありませんか？
特に最近よく見るのが、以下のような **`npm warn deprecated`** の群れです。

```bash
npm warn deprecated inflight@1.0.6: This module is not supported, and leaks memory. Do not use it...
npm warn deprecated rimraf@3.0.2: Rimraf versions prior to v4 are no longer supported
npm warn deprecated @humanwhocodes/config-array@0.13.0: Use @eslint/config-array instead
npm warn deprecated glob@7.2.3: Glob versions prior to v9 are no longer supported
```

「**Memory leak（メモリリーク）**」とか「**no longer supported（サポート終了）**」とか言われると不安になりますよね。

この記事では、この警告が出る理由と、**「無視していいのか？」「直すべきなのか？」の判断基準**を解説します。

## 結論：ユーザーからの質問への回答

> Q. 「npm warn deprecated」は無視していいですか？

**【結論】基本的には無視しても、直ちに影響はありません。**
アプリが動かなくなるわけではなく、「将来的にサポートされなくなるよ」「古い書き方だよ」という**作者からのアドバイス**に近いものです。

ただし、**「本番環境で動き続けるサーバー（Expressなど）」**を作っている場合は要注意なものもあります。
逆に、ビルドツールや Webpack, Vite, ESLint などの**「開発ツール」が出している警告なら、99% 無視して問題ありません。**

---

## 警告の中身を詳しく見てみる

質問にあった具体的な警告を例に、何が起きているのか解説します。

### ケース1: `inflight@1.0.6`
> `This module is not supported, and leaks memory.`

- **意味**: 「もうサポートしてないし、メモリリーク（メモリ解放忘れ）するバグがあるよ」
- **危険度**: ⚠ **中**
- **解説**: `inflight` は古い `glob` パッケージなどが内部で使っているライブラリです。
- **判断**:
    - **ビルドツールで出ているなら無視OK**（ビルドは数秒〜数分で終わるので、メモリリークしてもPCが落ちる前に終了するため）。
    - **24時間稼働するサーバーなら修正推奨**（いつかメモリ不足でサーバーが落ちる可能性があります）。

### ケース2: `rimraf@3.0.2` & `glob@7.2.3`
> `Versions prior to v4 (or v9) are no longer supported`

- **意味**: 「新しいメジャーバージョンが出たから、古いのはもう面倒みないよ」
- **危険度**: ℹ **低**
- **解説**: これは単なる世代交代のお知らせです。Node.js自体が新しくなり、標準機能で代用できるようになったため、古いライブラリの役割が終わろうとしています。
- **判断**: **無視してOK**。依存元のパッケージ（これを呼び出している親）が対応するのを待ちましょう。

### ケース3: `@humanwhocodes/...`
> `Use @eslint/config-array instead`

- **意味**: 「ライブラリの所有者が変わったから、新しい名前の方を使ってね」
- **危険度**: ℹ **低**
- **解説**: ESLint 関連のライブラリで最近よく出ます。開発者が個人の管理から、公式組織（ESLint）へ管理を移譲したためです。
- **判断**: **無視してOK**。ESLintのバージョンを上げれば自然と直ります。

---

## なぜ「入れてもいない」パッケージの警告が出るの？

「`package.json` に `inflight` なんて書いてないのに！」と思うかもしれません。
これは **「推移的依存関係（Transitive Dependencies）」** と呼ばれるものです。

あなたがインストールした A というツールが、裏で B を使い、B が C を使い、C が `inflight` を使っている……という構造になっています。

### 元凶を特定するコマンド
誰が古いパッケージを使っているのか犯人探しをする場合は、以下のコマンドを使います。

```bash
npm ls inflight
```

すると、以下のようなツリーが表示されます。

```text
my-project@1.0.0
└─┬ some-awesome-tool@5.0.0
  └─┬ glob@7.2.3
    └── inflight@1.0.6  <-- こいつが犯人！
```

この場合、あなたが直すべきは `inflight` ではなく、親である `some-awesome-tool` のアップデートです。

## 最終的な対処法まとめ

### 1. 無視する（推奨）
開発中にエラーが出て止まるわけでなければ、実害はありません。
特に `npm install` のログが汚れるのが嫌なだけであれば、スルーするのが精神衛生上もっとも良いです。

### 2. 親パッケージを更新する
```bash
npm outdated
npm update
```
これで親パッケージが新しくなり、依存関係も最新版（警告が出ない版）に切り替わることがあります。

### 3. オーバーライドする（上級者向け）
どうしても消したい場合、`package.json` に `overrides` を書くことで、強制的に新しいバージョンを使わせることができます。ただし、**壊れる可能性が高い**ので非推奨です。

```json
// package.json (非推奨の例)
"overrides": {
  "glob": "^10.0.0"
}
```

## まとめ

- 【結論】`npm warn deprecated` は「将来への注意喚起」であり、エラー（Error）ではない。
- 【ポイント】開発ツール（ビルド、Lint）で出る警告は、基本的に無視しても安全。
- 【対処】`npm update` しても消えない場合は、ライブラリの作者が対応するまで待つのが正解。

ターミナルの警告は心臓に悪いですが、中身を知れば怖くありません。今日も元気に開発を続けましょう！
