---
title: "【GASエラー解決集】Google Apps Scriptでよく出るエラーの原因と対処法まとめ"
slug: gas-error-summary-jp
date: 2025-12-14 10:00:00
categories: 
  - gas
  - summary
  - troubleshooting
tags: 
  - Google Apps Script
  - GAS
  - Error
  - まとめ
status: publish
featured_image: ../images/2025/gas-timeout-error.png
---

Google Apps Script (GAS) を書いていると、様々なエラーに遭遇します。
「Exceeded maximum execution time」や「INTERNAL Error」など、初心者泣かせのエラーから、Clasp特有のエラーまで、ZIDOOKA!で解説してきた解決記事をまとめました。

エラー名や症状から、該当する記事を探してみてください。

## 実行時間・タイムアウト系

### Exceeded maximum execution time
GASの「6分の壁」と呼ばれる有名なエラーです。スクリプトの実行時間が長すぎるときに発生します。

- [【GAS】「Exceeded maximum execution time」エラーの原因と、6分の壁を突破する回避策](/gas-exceeded-maximum-execution-time)
- [Google Apps Script｜「DEADLINE_EXCEEDED」エラーをやさしく解説します](/google-apps-script%ef%bd%9c%e3%80%8cdeadline_exceeded%e3%80%8d%e3%82%a8%e3%83%a9%e3%83%bc%e3%82%92%e3%82%84%e3%81%95%e3%81%97%e3%81%8f%e8%a7%a3%e8%aa%ac%e3%81%be%e3%81%99)

## 構文エラー・記述ミス系

### SyntaxError: Unexpected token / identifier
コードの書き間違いで発生するエラーです。全角スペースや閉じ忘れなど、意外な原因が多いです。

- [SyntaxError: Unexpected token- GAS初心者がこのエラーを出したときに、まず確認すべきポイント３つ（やさしい解説）](/syntaxerror-unexpected-token-gas%e5%88%9d%e5%bf%83%e8%80%85%e3%81%8c%e3%81%93%e3%81%ae%e3%82%a8%e3%83%a9%e3%83%bc%e3%82%92%e5%87%ba%e3%81%97%e3%81%9f%e3%81%a8%e3%81%8d%e3%81%ab%e3%80%81%e3%81%be)
- [GASで「SyntaxError: Unexpected identifier」が出たときに、まず見る場所と直す手順【初心者向け】](/gas%e3%81%a7%e3%80%8csyntaxerror-unexpected-identifier%e3%80%8d%e3%81%8c%e5%87%ba%e3%81%9f%e3%81%a8%e3%81%8d%e3%81%ab%e3%80%81%e3%81%be%e3%81%9a%e8%a6%8b%e3%82%8b%e5%a0%b4%e6%89%80%e3%81%a8%e7%9b%b4)

## 内部エラー・謎のエラー系

### INTERNAL Error / 内部エラー
コードは合っているはずなのに発生する、Google側の不具合や一時的な障害の可能性が高いエラーです。

- [Google Apps Script の “INTERNAL（内部エラー）” が突然出たときの原因と対処まとめ](/google-apps-script-%e3%81%ae-internal%ef%bc%88%e5%86%85%e9%83%a8%e3%82%a8%e3%83%a9%e3%83%bc%ef%bc%89-%e3%81%8c%e7%aa%81%e7%84%b6%e5%87%ba%e3%81%9f%e3%81%a8%e3%81%8d%e3%81%ae%e5%8e%9f)
- [GASで“INTERNAL（内部エラー）”が出るときに何が起きているのか？——エラー解決tips Error code INTERNAL](/gas%e3%81%a7internal%ef%bc%88%e5%86%85%e9%83%a8%e3%82%a8%e3%83%a9%e3%83%bc%ef%bc%89%e3%81%8c%e5%87%ba%e3%82%8b%e3%81%a8%e3%81%8d%e3%81%ab%e4%bd%95%e3%81%8c%e8%b5%b7%e3%81%8d%e3%81%a6)

## Clasp / 環境設定系

### User has not enabled the Apps Script API
Claspを使うときに必ず一度は遭遇する、API有効化忘れのエラーです。

- [このエラーの解決法は1分⇒clasp pushUser has not enabled the Apps Script API...](/%e3%81%93%e3%81%ae%e3%82%a8%e3%83%a9%e3%83%bc%e3%81%ae%e8%a7%a3%e6%b1%ba%e6%b3%95%e3%81%af1%e5%88%86%e2%87%92clasp-pushuser-has-not-enabled-the-apps-script-api-enable-it-by-visiting-https-script-go)

---

エラーはプログラミングの友達です。焦らず一つずつ解決していきましょう。
新しいエラー記事が増えたら、随時ここに追加していきます。
