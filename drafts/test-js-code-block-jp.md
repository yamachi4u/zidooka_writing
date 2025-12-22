---
title: "コードブロック言語テスト - JavaScript"
slug: "test-js-code-block-jp"
status: "draft"
categories:
  - "AI"
tags:
  - "Testing"
featured_image: "../images/image copy 16.png"
---

# コードブロック言語テスト - JavaScript

パイプラインで言語情報が正しく保持されているかをテストするシンプルなファイルです。

## テスト1: シンプルな関数

```javascript
function hello() {
  console.log("Hello, World!");
}

hello();
```

## テスト2: GASの例

```javascript
function getSheetData() {
  const sheet = SpreadsheetApp.getActiveSheet();
  const data = sheet.getDataRange().getValues();
  Logger.log(data);
  return data;
}
```

## テスト3: 複雑な例

```javascript
function processArray() {
  const arr = [1, 2, 3, 4, 5];
  const doubled = arr.map(x => x * 2);
  const sum = doubled.reduce((a, b) => a + b, 0);
  console.log("Doubled:", doubled);
  console.log("Sum:", sum);
}
```

このテストファイルは、HTML出力で言語クラスが `class="language-javascript"` として保持されているかを確認するためのものです。
