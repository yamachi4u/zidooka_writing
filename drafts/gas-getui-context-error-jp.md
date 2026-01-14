---
title: "【GAS】「Exception: Cannot call SpreadsheetApp.getUi() from this context」エラーの原因と回避策"
slug: gas-getui-context-error-jp
date: 2026-01-13 11:00:00
categories: 
  - GAS
  - GAS Tips
tags: 
  - troubleshooting
  - tips
  - Google Apps Script
  - GAS
  - Google Sheets
  - Error
status: publish
featured_image: ../images/gas-getui-context-error.png
---

GAS（Google Apps Script）で自動化を進めていると、よく遭遇するのが **「Exception: Cannot call SpreadsheetApp.getUi() from this context」** というエラーです。
手動でテストしたときは動くのに、トリガー実行に切り替えた途端に動かなくなるのが特徴です。

このエラーの意味と、3つの解決策を解説します。

## エラーの意味

```
Exception: Cannot call SpreadsheetApp.getUi() from this context.
```

これは、「**この実行方法（コンテキスト）では、UI（ダイアログ・アラート・メニュー）は使えません**」という意味です。
`SpreadsheetApp.getUi().alert()` などの UI 機能は、PCの前でスプレッドシートを開いている「人間」に対して表示するものです。誰も見ていない自動実行中には表示できません。

## エラーが起きる原因

ログにこのエラーが出ている場合、実行元はほぼ確実に以下のどれかです。

- ⛔ **時間主導トリガー**（毎日〇時実行など）
- ⛔ **onEdit / onChange**（自動実行されるトリガー）
- ⛔ **Webアプリ**（doGet / doPost）
- ⛔ **API実行** / バックグラウンド実行
- ⛔ **Driveトリガー**

👉 UI は「人間がスプレッドシートを開いて、メニューやボタンから手動実行したとき」しか使えません。

### よくあるNGコード

例えば、トリガーで実行する関数の中にこんなコードが入っているとアウトです。

```javascript
// トリガー実行だとここで落ちる
SpreadsheetApp.getUi().alert('完了しました');
```

```javascript
// これもNG
const ui = SpreadsheetApp.getUi();
ui.alert('エラーです');
```

## 解決策（3つの選択肢）

### 解決策①：UI を完全にやめる（最も安全）

トリガーによる完全自動化を目指すなら、UI 表示はすべて削除しましょう。これが一番確実です。

**ログ出力に切り替える：**

```javascript
console.log('処理完了');
// または
Logger.log('処理完了');
```

**セルに結果を出力する：**

```javascript
sheet.getRange('A1').setValue('実行完了: ' + new Date());
```

### 解決策②：UI を使う処理と分離する（おすすめ構成）

ZIDOOKA! でも推奨している、最もきれいな設計パターンです。「処理本体（Core）」と「手動実行用ラッパー（Manual）」に関数を分けます。

```javascript
/**
 * UIを使わない本体処理
 * トリガーにはこの関数を設定する
 */
function exportFileListToSheetCore() {
  // ここにメインロジックを書く
  // UI操作は一切行わない
  console.log('処理開始...');
}

/**
 * 手動実行用の関数
 * ボタンやメニューにはこの関数を割り当てる
 */
function exportFileListToSheetManual() {
  // 本体処理を呼び出し
  exportFileListToSheetCore();
  
  // 人が見ている時だけメッセージを出す
  SpreadsheetApp.getUi().alert('完了しました');
}
```

- ⏱ **トリガー設定**: `exportFileListToSheetCore` を指定
- 👤 **ボタン割り当て**: `exportFileListToSheetManual` を指定

こうすれば、ロジックを共通化しつつ、手動実行時だけ親切なメッセージを出すことができます。

### 解決策③：実行元で分岐する（上級者向け）

どうしても1つの関数で済ませたい場合、`try-catch` で UI 呼び出しのエラーを握りつぶす方法もあります。

```javascript
function safeAlert(message) {
  try {
    SpreadsheetApp.getUi().alert(message);
  } catch (e) {
    // UIが使えない（トリガー実行）場合は、ログに出してスルーする
    Logger.log('バックグラウンド実行のためログのみ出力: ' + message);
  }
}
```

> 【結論】実装可能ですが、設計としては **解決策②（分離）** のほうが明確でおすすめです。

## まとめ

- 【結論】トリガー / 自動実行・OnEdit で `getUi()` は使えない。
- 【注意】`Exception: Cannot call SpreadsheetApp.getUi()` は実行コンテキストの誤りが原因。
- 【対処】処理本体と UI 表示部分を関数レベルで分離する設計にする。

ログにエラーが出ている行番号（例: `mainFunction.gs:2445`など）を確認し、そこにある `getUi()` を削除するか、分離してください。
