---
title: "Vivliostyle CLIが認識されない時の原因と解決手順【PowerShell / Windows / npm】"
date: 2026-01-02 19:30:00
categories: 
  - Windows / Desktop Errors
  - WEB制作
tags: 
  - Vivliostyle
  - CLI
  - PowerShell
  - Node.js
  - npm
  - Troubleshooting
status: publish
slug: vivliostyle-command-not-found
featured_image: ../images/202601/vivliostyle-error.png
---

Vivliostyle CLI を使おうとして `vivliostyle --version` を実行したところ、以下のようなエラーに遭遇したことはありませんか？

```powershell
vivliostyle : 用語 'vivliostyle' は、コマンドレット、関数、スクリプト ファイル、
または操作可能なプログラムの名前として認識されません。
```

これは CLI が正しくインストールされていないか、システム PATH に登録されていないことが原因です。本記事では、なぜこのエラーが出るのか、そして確実に Vivliostyle CLI を使えるようにする方法を丁寧に解説します。

## Vivliostyle CLI とは
Vivliostyle CLI は、HTML と CSS を用いた組版をコマンドラインで行うためのツールです。公式サイトでも CLI の導入を推奨しており、PDF 生成やプレビュー表示が行えます。

## なぜ Vivliostyle が認識されないのか？
PowerShell で `vivliostyle` が見つからない理由は主に次の 3 つです。

### 1. Node.js がインストールされていない
Vivliostyle は Node.js のパッケージとして提供されています。npm を使える環境が必要です。
（`node -v`, `npm -v` コマンドで確認できます）

### 2. Vivliostyle CLI をインストールしていない
`npm install -g @vivliostyle/cli` を実行していない場合、コマンドとして登録されません。

### 3. npm global の PATH が通っていない
Windows では `C:\Users\<ユーザー名>\AppData\Roaming\npm` が PATH に含まれていないと CLI が見つからないことがあります。

## 解決手順（Windows / PowerShell）
以下の手順で確実に CLI を動かせるようにします。

:::step
**Step 1: Node.js のインストール**
[Node.js公式サイト](https://nodejs.org) から LTS 版をインストールします。
:::

:::step
**Step 2: Vivliostyle CLI のインストール**
PowerShell で次のコマンドを実行します：

```powershell
npm install -g @vivliostyle/cli
```
:::

:::step
**Step 3: PATH の確認とバージョン確認**
Node の global インストール先を PATH に追加します（通常はインストーラーが自動で行いますが、再起動が必要な場合があります）。

```powershell
vivliostyle --version
```
:::

:::note
**npx で実行する方法**
インストールせずに一時実行したい場合、`npx` を使うと便利です。

```powershell
npx @vivliostyle/cli --version
```
これだけで Vivliostyle CLI を試せます。
:::

## まとめ
エラーの原因は CLI がシステムに登録されていないだけです。Node.js 環境の整備と npm でのインストール、PATH の確認をすることで、CLI は正常に動作します。

