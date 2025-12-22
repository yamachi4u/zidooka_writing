---
title: "Authorization is required to perform that action が出る原因と対処法【GAS / API】"
slug: gas-authorization-error-jp
date: 2025-12-17 12:10:00
categories: 
  - gas-errors
  - gas
  - google-errors
  - naerror
tags: 
  - GAS
  - Google Apps Script
  - API
  - Authorization
  - OAuth
  - Error
status: publish
featured_image: ../images/gas-authorization-error.png
---

Google Apps Script（GAS）や各種 API を使った処理で、
`Authorization is required to perform that action` というエラーが出ることがあります。

このエラーは一言で言うと、
**「その操作を実行する権限がありません」**
という意味です。

ただし実務では、

*   何の権限が足りないのか
*   どこで認証が切れているのか
*   自分のコードが悪いのか、設定が悪いのか

が分かりにくく、詰まりやすいエラーでもあります。

この記事では、なぜ起きるのか／実務で多い原因／確認ポイント／対処法を整理します。

## このエラーは何を意味しているのか

`Authorization is required to perform that action` は、

*   API
*   Googleサービス（Drive / Sheets / Gmail など）
*   WordPress REST API
*   外部SaaS

に対して、必要な認証・権限が付与されていない状態で操作しようとしたときに返されます。

重要なのは、このエラーは

*   処理内容が間違っている

のではなく

*   **実行者の権限が足りていない**

という点です。

## よくある原因①：GASの認可が未実行・不完全

GASでは、初回実行時やスコープ変更時に**認可（Authorization）**が必要です。

以下のようなケースで頻発します。

*   新しい Google サービスを使い始めた
*   Drive / Gmail / Calendar を追加で使った
*   マニフェスト（appsscript.json）を編集した
*   トリガーから実行した

👉 **エディタ上で一度手動実行し、認可画面を通すのが基本です。**

## よくある原因②：トリガー実行時の権限不足

時間主導トリガーやフォームトリガーでは、
実行ユーザーの権限が問題になることがあります。

*   作成者以外の権限で動いている
*   サービスアカウント的に実行されている
*   アクセスできない Drive / Spreadsheet を触っている

この場合、エラーは出ても
コード自体は正しいことがほとんどです。

## よくある原因③：OAuth スコープが足りていない

GASでは、使うサービスごとに OAuth スコープが必要です。

例えば：

*   Drive を操作 → Drive スコープ
*   Gmail を送信 → Gmail スコープ
*   外部 API を叩く → 外部通信スコープ

`appsscript.json` を手動編集している場合、
スコープ不足によってこのエラーが出ることがあります。

👉 **自動スコープに任せている場合でも、一度再認可を通すことで解消するケースが多いです。**

## よくある原因④：外部APIの認証ヘッダー不足・期限切れ

外部APIを `UrlFetchApp.fetch()` で叩いている場合、
以下が原因になることが非常に多いです。

*   Authorization ヘッダーが未設定
*   Bearer トークンの期限切れ
*   APIキーが間違っている
*   APIキーはあるが権限が足りない

```javascript
const res = UrlFetchApp.fetch(url, {
  headers: {
    Authorization: "Bearer YOUR_TOKEN"
  }
});
```

401 / 403 と一緒にこのエラー文言が返るケースは典型例です。

## よくある原因⑤：操作対象そのものに権限がない

GAS側ではなく、対象リソース側の権限不足もよくあります。

*   共有されていない Drive ファイル
*   編集権限のない Spreadsheet
*   管理者専用のAPI操作
*   WordPressで管理者権限が必要なエンドポイント

この場合、
「認証は通っているが、操作が拒否されている」状態です。

## デバッグ時に必ず確認すべきポイント

エラーが出たら、以下を順に確認します。

1.  エディタ上で手動実行して認可を通したか
2.  実行ユーザーは誰か（トリガー含む）
3.  操作対象の権限はあるか
4.  外部APIの Authorization ヘッダーは正しいか
5.  トークンやAPIキーは有効か

外部APIの場合は、必ず以下をログ出力します。

```javascript
const res = UrlFetchApp.fetch(url, {
  muteHttpExceptions: true
});

Logger.log(res.getResponseCode());
Logger.log(res.getContentText());
```

これで 401 / 403 / メッセージ内容がはっきり見えます。

## 実務での考え方（重要）

このエラーが出たときは、

*   ロジックを疑う前に
*   `JSON.parse` を疑う前に

**「誰の権限で、何をしようとしているか」**を整理するのが最短ルートです。

コードが正しくても、
権限がなければ必ず失敗します。

## まとめ

*   `Authorization is required to perform that action` は 権限不足エラー
*   GASの認可未実行・トリガー実行が原因になりやすい
*   OAuthスコープ不足でも発生する
*   外部APIでは Authorization ヘッダーを必ず確認
*   対象リソース側の権限も要チェック

このエラーは「難しい」よりも、
確認ポイントを外すと一生ハマるタイプのエラーです。

権限・実行者・対象リソース。
この3点を切り分けることで、ほぼ確実に解消できます。
