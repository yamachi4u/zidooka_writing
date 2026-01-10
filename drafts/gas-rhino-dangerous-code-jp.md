---
id: 2871
title: "Rhino時代のコピペコードが危険な理由：V8では壊れる「古いGASの書き方」5選【初心者向け】"
date: 2025-12-23
thumbnail: images/2025/gas-rhino-deprecation-warning.png
categories: 
  - GAS Tips
slug: "gas-rhino-dangerous-code-examples-jp"
---

Google Apps Script（GAS）を調べていると、Qiita・ブログ・古い公式ドキュメントなどからコードをコピペすることがあります。

しかし注意が必要です。

そのコード、**Rhino時代の書き方**かもしれません。
そして **Rhino は 2026年1月31日でサポート終了**。
V8では「そのままでは壊れる」書き方が、実際に存在します。

この記事では、V8に切り替えたときに壊れやすい Rhino時代の書き方を5つ、
「なぜダメか」「どう直すか」まで含めて解説します。

## 前提：なぜ「Rhino時代のコード」が危険なのか

:::note
Rhinoは、

*   JavaScriptの仕様が古い
*   独自の挙動（＝今の標準とズレている）がある
*   「動いてしまう」書き方が多かった

一方、V8は、

*   JavaScriptの標準仕様に厳密
*   曖昧な書き方はエラーとして止める
*   ブラウザ（Chrome）と同じ感覚で動く
:::

つまり、

**Rhinoで「たまたま動いていたコード」が**
**V8では「普通にエラーになる」**

という現象が起きます。

## ダメな例①：for each ... in を使っている

### ❌ Rhino時代によくあるコード

:::example
```javascript
for each (var row in values) {
  Logger.log(row);
}
```
:::

### なぜダメ？

`for each ... in` は JavaScriptの標準仕様ではありません。
Rhino独自の拡張です。

👉 **V8では 完全にサポートされていない ため、エラーになります。**

### ✅ V8での正しい書き方

:::example
```javascript
for (var row of values) {
  console.log(row);
}
```

または

```javascript
values.forEach(function(row) {
  console.log(row);
});
```
:::

## ダメな例②：var 前提のスコープ依存コード

### ❌ Rhino時代にありがちなコード

:::example
```javascript
for (var i = 0; i < 5; i++) {
  setTimeout(function() {
    Logger.log(i);
  }, 1000);
}
```
:::

### 何が起きる？

Rhinoでは「なんとなく期待通り」に動いていたケースがありますが、
V8では **全て同じ値（5）になる** など、挙動が明確になります。

### なぜダメ？

`var` は 関数スコープ。
V8ではこの挙動がより厳密に評価され、バグが顕在化します。

### ✅ V8での正解

:::example
```javascript
for (let i = 0; i < 5; i++) {
  setTimeout(function() {
    console.log(i);
  }, 1000);
}
```
:::

👉 **V8では `let` / `const` を使うのが前提。**

## ダメな例③：予約語を変数名に使っている

### ❌ Rhinoでは動いてしまう例

:::example
```javascript
var class = 'test';
var default = 1;
```
:::

### なぜダメ？

`class` や `default` は JavaScriptの予約語。

*   Rhino：黙って許していた
*   V8：**即エラー**

### ✅ V8での修正例

:::example
```javascript
var className = 'test';
var defaultValue = 1;
```
:::

👉 **予約語を変数名にしない。**
古い記事ほどこの地雷が多いです。

## ダメな例④：Date.getYear() を使っている

### ❌ Rhino時代に多いコード

:::example
```javascript
var year = new Date().getYear();
```
:::

### 何がダメ？

`getYear()` は 仕様上かなりクセがあるメソッド。

*   2025年 → `125` が返る
*   Rhinoでは気づかず使われがち
*   V8では「仕様通り」動くため、年がおかしくなる。

### ✅ 正しい書き方

:::example
```javascript
var year = new Date().getFullYear();
```
:::

👉 これは V8移行で実害が出やすい代表例です。

## ダメな例⑤：暗黙のグローバル変数

### ❌ Rhinoで通っていたコード

:::example
```javascript
function test() {
  x = 10;
}
```
:::

### なぜダメ？

`var` / `let` / `const` を付けていない変数は、

*   Rhino：暗黙でグローバル扱い
*   V8：エラー or 予期しない動作

### ✅ V8での正解

:::example
```javascript
function test() {
  let x = 10;
}
```
:::

👉 **V8では「宣言しない変数」は事故の元。**

## なぜ「コピペコード」が一番危険なのか

1.  書いた本人が挙動を理解していない
2.  Rhino前提で書かれているか判別しにくい
3.  「動いているからOK」と思い込む

:::warning
そして V8 に切り替えた瞬間、

*   エラーが出る
*   トリガーが止まる
*   自動処理が沈黙する

という形で **事故が表面化** します。
:::

## ZIDOOKA!的まとめ

:::conclusion
*   Rhino時代のコードは「動いていた＝正しい」ではない
*   V8は JavaScriptの標準に忠実

特に危険なのは

1.  `for each`
2.  `var`依存
3.  予約語変数
4.  `getYear()`
5.  暗黙グローバル

V8移行＝コードを書き直す必要はないことが多いですが、
**「古いコピペ」は必ず疑うべき**です。
:::

---

**【まとめ記事】**
この記事を含む「Rhino廃止・V8移行」に関する全情報をまとめたガイドはこちら。
👉 **[GAS Rhino廃止・V8移行完全ガイド：3つの記事で完璧に理解する](https://www.zidooka.com/archives/2877)**
