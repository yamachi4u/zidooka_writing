---
title: "Error: Invalid JSON string が出る原因と対処法【GAS / JavaScript】"
slug: gas-invalid-json-string-jp
date: 2025-12-17 12:00:00
categories: 
  - gas-errors
tags: 
  - GAS
  - JavaScript
  - JSON
  - Error
status: publish
featured_image: ../images/2025/gas-invalid-json.png
---

Google Apps Script（GAS）や JavaScript で API 連携やデータ処理を行っていると、
`Error: Invalid JSON string` というエラーに遭遇することがあります。

このエラーは、「JSONとして処理しようとした値が、JSONの形式になっていない」場合に発生します。
一見単純なエラーですが、実務では原因がレスポンス側にあり、気づきにくいケースが非常に多いのが特徴です。

この記事では、なぜ起きるのか／どこを見るべきか／どう直すかを、GAS実務前提で整理します。

## このエラーは何を意味しているのか

`Invalid JSON string` は、多くの場合 `JSON.parse()` 実行時に発生します。

```javascript
JSON.parse(someString);
```

ここで `someString` が **正しいJSON形式でない** 場合、例外が投げられます。

重要なのは、このエラーが意味しているのは、

*   JSONが壊れている

のではなく

*   **JSONだと思って処理しているものが、そもそもJSONではない**

という点です。

実務では「JSONが不正」というより、想定外の文字列を parse していることがほとんどです。

## よくある原因①：JSONではない文字列を parse している

最も多い原因です。

```javascript
const text = "hello world";
JSON.parse(text); // Error: Invalid JSON string
```

JSONとして扱われるデータは、多くの場合オブジェクトまたは配列です。

```json
{"key":"value"}
```

```json
["a", "b", "c"]
```

JSON仕様上は数値や文字列もJSON値として有効ですが、
APIレスポンスとして扱うJSONは、ほぼ例外なくオブジェクトか配列です。

それ以外が返ってきた時点で、「想定外」と考えてよいケースがほとんどです。

## よくある原因②：APIのレスポンスが想定と違う（GASで特に多い）

GASでは `UrlFetchApp.fetch()` を使ったAPI連携が非常に多く、
ここが `Invalid JSON string` の最大の発生源になります。

```javascript
const res = UrlFetchApp.fetch(url);
const data = JSON.parse(res.getContentText());
```

このとき、実際に返ってきているのが：

*   HTML（エラーページ）
*   空文字
*   認証エラーメッセージ
*   JSON「っぽい」テキスト（実際はJSONではない）

といったケースは珍しくありません。

特に多い原因は以下です。

*   401 / 403 エラー
*   APIキー未設定・期限切れ
*   リクエスト制限超過

👉 **まず `getContentText()` をそのままログに出すのが鉄則です。**

### GAS実務で必須：レスポンスを確認する書き方

HTTPエラー時の中身を確認するため、
GASでは以下の書き方が非常に有効です。

```javascript
const res = UrlFetchApp.fetch(url, {
  muteHttpExceptions: true
});

Logger.log(res.getResponseCode());
Logger.log(res.getContentText());
```

`muteHttpExceptions` を使うことで、
エラー時でもレスポンス本文を確認できるようになります。

「`JSON.parse` が失敗した」のではなく、
**そもそもJSONが返ってきていない**ことに気づくための重要なポイントです。

## よくある原因③：JSON以外のフォーマットが混ざっている

以下のようなケースも頻発します。

*   エラーメッセージやHTMLが混入している
*   改行や制御文字が含まれている
*   CSV / TSV を JSON と勘違いしている
*   末尾に余分なカンマがある

```json
{"name":"test",}
```

人間には分かりやすく見えても、JSONとしては不正です。

実務では「JSONが壊れている」というより、
JSON以外の形式が混ざっているケースが大半です。

## よくある原因④：二重に JSON.parse している

地味ですが、実務で非常に多いパターンです。

```javascript
const obj = JSON.parse(jsonString);
JSON.parse(obj); // Invalid JSON string
```

一度 `JSON.parse` した値は、すでにオブジェクトです。
再度 parse する必要はありません。

ログ出力やデバッグ中に、誤って再 parse しているケースがよくあります。

## デバッグ時に必ずやるべきチェック

エラーが出たら、次の順番で確認します。

1.  型を確認する
2.  中身をそのまま出力する
3.  本当に `JSON.parse` が必要か考える

```javascript
Logger.log(typeof value);
Logger.log(value);
```

これだけでも、原因が即座に判明することは珍しくありません。

## 安全な書き方（防御的実装）

実務では、例外を前提にした書き方がおすすめです。

```javascript
function safeParseJson(text) {
  try {
    return JSON.parse(text);
  } catch (e) {
    Logger.log("Invalid JSON: " + text);
    return null;
  }
}
```

この関数を使う場合、戻り値が `null` になる可能性を必ず考慮してください。
そのままプロパティ参照すると、別のエラーを引き起こします。

## まとめ

*   `Invalid JSON string` は JSON形式でないものを parse しているサイン
*   原因の多くは APIレスポンスの想定違い
*   まずは「中身をそのまま確認する」
*   `muteHttpExceptions` を使ってレスポンスを見る
*   二重 parse に注意する
*   実務では try-catch による防御が必須

JSONは「データ形式」です。
思い込みで処理せず、実際に返ってきているものを見ることが最大の対策になります。
