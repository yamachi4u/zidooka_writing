---
title: "Google Apps Scriptで「ERR_TOO_MANY_REDIRECTS」が出たときの話"
date: 2025-12-24 10:00:00
categories: 
  - GAS Tips
  - エラー全集
tags: 
  - gas-tips
  - gas-errors
  - Google Apps Script
  - トラブルシューティング
status: publish
slug: gas-err-too-many-redirects
featured_image: ../images/2025/gas-timeout-error.png
---

Google Apps Script（GAS）の管理画面を開こうとしたところ、以下のエラーが表示されてアクセスできない現象に遭遇しました。

:::warning
**このページは動作していません**
script.google.com でリダイレクトが繰り返し行われました。
ERR_TOO_MANY_REDIRECTS
:::

コードの実行エラーではなく、**GASの管理画面（エディタ）自体が開けない**という状況です。
この現象について、発生状況と対処法をメモとして残しておきます。

## 発生した状況

- **操作:** ブラウザのブックマークからGASのプロジェクトを開こうとした
- **タイミング:** 久しぶりにアクセスしたとき
- **エラー内容:** `ERR_TOO_MANY_REDIRECTS`（リダイレクトが多すぎる）

スクリプト自体のバグではなく、ブラウザとGoogleアカウントの認証セッションの間で何らかの不整合が起きているようでした。

## 対処法：Cookieの削除

このエラーは「Cookieの削除」で解消することが多いです。

:::step
1. ブラウザの設定を開く
2. 「閲覧履歴データの削除」を選択
3. 「Cookieと他のサイトデータ」にチェックを入れて削除
:::

ただし、これを行うと他のGoogleサービスからもログアウトされてしまうため、まずは**シークレットウィンドウ（プライベートブラウジング）**で開けるか試すのがおすすめです。
シークレットウィンドウで開ける場合は、ブラウザのキャッシュやCookieが原因である可能性が濃厚です。

## 原因は特定できないことが多い

このエラーは「これをやったら確実に直る」というよりは、ブラウザの状態やログイン状況に依存する一時的なトラブルであることが多いです。

:::note
Google Apps Scriptでは、コードの問題ではなく状態や認証まわりで原因不明のエラーが起きることがあります。
その考え方については、以下の記事で整理しています。

[Google Apps Scriptが開けない・進まないときの考え方（概念整理）](https://www.zidooka.com/archives/2937)
:::
