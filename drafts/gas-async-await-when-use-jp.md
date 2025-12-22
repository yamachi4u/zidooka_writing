---
title: "GASでasync/awaitはいつ使える？使えない？【実務目線で整理】"
date: 2025-02-15 19:00:00
categories: 
  - GAStips
tags: 
  - GAS
  - Google Apps Script
  - async/await
  - JavaScript
  - パフォーマンス
status: publish
slug: gas-async-await-when-use-jp
---

Google Apps Script（GAS）でコードを書いていると、JavaScriptの文脈から「async / await を使えば非同期で速くなるのでは？」と考える方は少なくありません。

しかし、GASにおける async / await は **使える場面と、ほぼ意味を持たない場面がはっきり分かれています**。この誤解により、不要な複雑性が増すことも少なくありません。

この記事では、GASの実行モデルを前提に、async / await の使いどころを実務目線で整理します。

## 【結論】GASで async / await は「限定的に」使える

結論から言うと、GASで async / await は **構文としては使えますが、パフォーマンス改善や並列処理にはほぼ寄与しません**。

使いどころを一言でまとめると：

> Promise を返す処理を、読みやすく安全に書くための構文

です。**速度改善は期待できません。**

## 【前提】V8ランタイムが必要

現在のGASはデフォルトでV8ランタイムを採用しており、以下のように async 関数を問題なく定義できます。

```javascript
async function test() {
  return "ok";
}
```

この時点でエラーになることはありません。ただし、構文が使えることと「実用的に使える」ことは別です。

## 【有効なケース1】Promiseを返す自作関数をawaitするとき

async / await が本来の意味を持つのは、**await の対象が実際に Promise である場合のみ**です。

```javascript
function wait(ms) {
  return new Promise(resolve => {
    Utilities.sleep(ms);
    resolve();
  });
}

async function main() {
  Logger.log("start");
  await wait(1000);
  Logger.log("end");
}
```

このケースでは、await は処理の順序制御として有効です。ただし、実行速度は速くなりません。Utilities.sleep() で1秒待つので、1秒待たされます。

**メリット**：順序が明確になり、thenチェーンよりも読みやすい

**デメリット**：実行時間は変わらない

## 【有効なケース2】非同期APIをPromise化したとき

GASの `UrlFetchApp.fetch()` は同期APIですが、自分で Promise ラップすることで async / await の恩恵を受けられます。

```javascript
function fetchAsync(url) {
  return new Promise(resolve => {
    try {
      const res = UrlFetchApp.fetch(url);
      resolve(res);
    } catch (e) {
      throw e;
    }
  });
}

async function main() {
  Logger.log("start");
  const res = await fetchAsync("https://example.com");
  Logger.log("response received");
  Logger.log(res.getContentText());
}
```

**メリット**：thenチェーンを使わずに可読性の高いコードが書ける

**注意**：裏側で並列処理されるわけではなく、あくまでコード構造の改善です。

## 【有効なケース3】HTMLService（Webアプリ・サイドバー）

GAS本体とは対照的に、HTMLService 側（ブラウザ上のJavaScript）では async / await は **非常に有効** です。

```javascript
async function callGAS() {
  const result = await new Promise((resolve, reject) => {
    google.script.run
      .withSuccessHandler(resolve)
      .withFailureHandler(reject)
      .serverFunction();
  });
  console.log(result);
}

// 実際の使用
callGAS();
```

Webアプリ、サイドバー、ダイアログを作る場合、async / await は **積極的に使う価値があります**。ブラウザ側は真の非同期実行が可能だからです。

## 【無意味なケース1】標準サービスの直接呼び出し

SpreadsheetApp、DriveApp、GmailApp など、GAS標準サービスは **完全同期API** です。

```javascript
// ❌ これは意味を持たない
async function badExample() {
  const sheet = SpreadsheetApp.getActiveSheet();
  await sheet.getRange("A1").getValue();  // ← Promise を返していない
}

// ✅ 正しい使い方
function goodExample() {
  const sheet = SpreadsheetApp.getActiveSheet();
  const value = sheet.getRange("A1").getValue();
}
```

await を書いても：
- Promise が返ってこない
- 非同期化されない
- 実行速度は変わらない

単に読みにくくなるだけです。

## 【無意味なケース2】並列処理を期待するケース

```javascript
// ❌ JavaScriptの文法上は正しくても、GASでは逐次実行
async function parallelFail() {
  await funcA();  // 1秒
  await funcB();  // 1秒
  // 合計2秒かかる（並列ではない）
}

// Promise.all() でも同じ
async function parallelFail2() {
  await Promise.all([funcA(), funcB()]);
  // GASでは結果的に逐次実行。速度改善なし。
}
```

GASの実行環境では、`Promise.all()` を使ってもGAS API の呼び出しは **実質的に並列にはなりません**。

## 【実務での判断基準】こう使い分けよう

### ✅ async / await を使ってよいケース

- Promiseを返す自作関数がある
- thenチェーンを避けたい
- HTMLService 側のJavaScript
- コード可読性を重視したい場面

### ❌ async / await を使わない方がよいケース

- Spreadsheet、Drive、Gmail 操作
- 処理速度を上げたいだけの場合
- 単純なバッチ処理
- 複数の API 呼び出しを並列化したい場合

## 【よくある誤解】整理表

| 期待              | 実際              | 正しい理解                 |
| --------------- | --------------- | --------------------- |
| asyncにすると速くなる | 速くならない         | コード構造の改善に過ぎない       |
| awaitで並列処理できる | GASではできない      | Promise 待機でも実行は逐次  |
| async = 非同期実行 | Promise前提       | Promise がなければ意味なし   |
| 複数APIを並列化可能 | 逐次実行になる       | GAS標準サービスは同期API      |

## 【まとめ】GASの async / await の立ち位置

GASにおける async / await は：

* **非同期処理の万能解決策ではない**
* **パフォーマンス改善目的では使えない**
* **Promise設計のコードを整理するための構文**

という位置づけです。

この理解があれば、不要な複雑性を避け、本当に必要な場面でだけ活用できます。

誤解したまま使うと、コード量は増えるのに実行速度は変わらず、むしろ保守性が下がる、という落とし穴に陥りがちです。用途を正確に理解したうえで、必要な場面だけに絞って使うのが最適解です。
