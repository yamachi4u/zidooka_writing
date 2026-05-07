---
title: "GASの承認画面が変わった？「Google hasn't verified this app」「currently being tested」と出る理由"
categories:
  - GAStips
tags:
  - GAS
  - Google Apps Script
  - OAuth
  - Google Cloud
  - トラブルシューティング
status: publish
slug: gas-oauth-screen-changed-jp
---

Google Apps Script（GAS）や Google OAuth を使ったツールを承認しようとしたとき、以前は `Advanced` や `Go to {app} (unsafe)` が出ていたのに、最近は次のような画面が出ることがあります。

> Google hasn't verified this app  
> You've been given access to an app that's currently being tested.

下にあるボタンも、以前の「安全ではないページに移動」ではなく、`Continue` と `Back to safety` です。UI が変わったので不安になりますが、意味としては「Google Cloud 側でこの OAuth アプリが ==Testing== のままで、あなたが ==test user== として招待されている」という警告に近いです。

## 結論

自分で作った GAS や、開発者が分かっている社内ツールなら、`Continue` で進んでよいケースがあります。逆に、誰が作ったか分からない OAuth アプリなら進まないほうが安全です。

Google の公式ヘルプでも、`Testing` のアプリはテストユーザーに限定され、警告画面が表示されること、さらに承認が 7 日で切れることが案内されています。

- [Manage App Audience](https://support.google.com/cloud/answer/15549945?hl=en-GB)
- [OAuth Client Verification for Apps Script](https://developers.google.com/apps-script/guides/client-verification)

## なぜ画面が変わったのか

Google は OAuth 同意画面まわりを段階的に整理していて、現在は `Google Auth platform` の `Audience` と `Publishing status` で、アプリが `Testing` か `In production` かを管理する形になっています。

公式ドキュメントでは、外部向けアプリを `Testing` にしている場合は、追加したテストユーザーだけが承認でき、Google が warning message を表示すると説明されています。今回の `currently being tested` 画面は、その tester warning の新しい見た目だと考えるのが自然です。

これは Google のヘルプに書かれている挙動と、実際の画面表示を合わせた判断です。

## この画面が出る主な条件

1. OAuth アプリの user type が `External`
2. publishing status が `Testing`
3. あなたの Google アカウントが `Test users` に追加されている
4. そのアプリが sensitive scope などを使っている

この条件だと、Google は承認前に「検証済みではない」「現在テスト中」という警告を出します。

## Continue してよいケース

- 自分で作った GAS である
- 自社や知人開発のツールで、誰が作ったか分かっている
- 取得される権限の内容を読んで納得できる
- Google Cloud の `OAuth consent screen` で自分を `Test users` に追加した直後である

## Continue しないほうがいいケース

- 開発者が不明
- メールや DM で突然送られてきた
- 求めている権限が用途に対して広すぎる
- ドキュメントや配布元が確認できない

## 開発者側の対処法

この画面を出したくないなら、Google Cloud Console で OAuth 設定を見直します。

1. `Google Auth platform` を開く
2. `Audience` で `Testing` になっているか確認する
3. 開発中なら `Test users` に必要なアカウントだけ追加する
4. 一般公開したいなら `In production` を検討する
5. sensitive / restricted scopes を使う場合は verification の準備を進める

Google の公式では、開発・検証段階のアプリは `Testing` のままでよい一方、一般公開したいなら verification が必要になると説明しています。

- [Configure the OAuth consent screen and choose scopes](https://developers.google.com/workspace/guides/configure-oauth-consent)
- [When is verification not needed](https://support.google.com/cloud/answer/13464323?hl=en)

## 7日でまた認証を求められることがある

これも地味に重要です。`Testing` 状態のアプリは、公式ヘルプ上で「承認は 7 日で期限切れになる」と案内されています。offline access を使っている場合、refresh token も期限切れになります。

毎回しばらくすると再認証を求められるなら、壊れているのではなく `Testing` の仕様である可能性が高いです。

## 以前の「Advanced」が出ないのは不具合？

不具合とは限りません。以前は `Advanced` から `unsafe` に進む UI を見ていた人でも、現在は `Continue` ベースの tester warning に変わっていることがあります。

つまり、文言とボタンは変わっても、実態としては

- 未検証アプリ
- テストユーザー限定
- 開発中アプリ向けの警告

という理解でだいたい合っています。

## 関連記事

- [【GAS】「このアプリはGoogleによって確認されていません」と出た時の承認手順（認証回避）](https://www.zidooka.com/archives/2032)
