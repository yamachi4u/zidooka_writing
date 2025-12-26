---
title: "Google Apps Scriptが開けない原因が「リダイレクト」だった話"
date: 2025-12-24 10:05:00
categories: 
  - GAS Tips
  - エラー全集
tags: 
  - gas-tips
  - gas-errors
  - Google Apps Script
  - 初心者向け
status: publish
slug: gas-wont-open-redirect-loop
featured_image: ../images/gas-timeout-error.png
---

「Google Apps Scriptが開けない」「画面が真っ白になる」というトラブルに遭遇しました。
よく見ると、ブラウザのアドレスバーがパチパチと切り替わり、最終的にエラー画面が表示されていました。

## 症状：ページが表示されない

GASのコードを編集しようとしただけなのに、以下のような画面になってしまいました。

:::warning
**このページは動作していません**
script.google.com でリダイレクトが繰り返し行われました。
:::

「リダイレクト」とは、ページから別のページへ自動的に転送されることです。
これが無限ループしてしまい、ブラウザが「これ以上は無理」と判断して止めた状態です。

## 何も壊していないので安心してほしい

このエラーが出ると「GASのコードを壊してしまったのではないか？」「データが消えたのではないか？」と不安になりますが、**データは無事である可能性が高い**です。

これはGASの中身ではなく、**ブラウザとGoogleの間の「通行証（Cookie）」のやり取りがうまくいっていない**だけの状態です。

## 対処法

まずは**シークレットモード**（Chromeなら `Ctrl + Shift + N`）で同じURLを開いてみてください。
これで開けるなら、普段使っているブラウザの「Cookie（クッキー）」を削除すれば直ります。

:::note
Google Apps Scriptでは、このように「コードは悪くないのに動かない」というケースが多々あります。
原因不明のエラーに遭遇したときの心構えについては、以下の記事で詳しく解説しています。

[Google Apps Scriptが開けない・進まないときの考え方（概念整理）](https://www.zidooka.com/archives/2937)
:::
