---
title: "Code Block Language Test - JavaScript"
slug: "test-js-code-block-en"
status: "draft"
categories:
  - "AI"
tags:
  - "Testing"
featured_image: "../images/2025/image copy 16.png"
---

# Code Block Language Test - JavaScript

This is a simple test to verify that the language information is being preserved through the pipeline.

## Test 1: Simple Function

```javascript
function hello() {
  console.log("Hello, World!");
}

hello();
```

## Test 2: GAS Example

```javascript
function getSheetData() {
  const sheet = SpreadsheetApp.getActiveSheet();
  const data = sheet.getDataRange().getValues();
  Logger.log(data);
  return data;
}
```

## Test 3: Complex Example

```javascript
function processArray() {
  const arr = [1, 2, 3, 4, 5];
  const doubled = arr.map(x => x * 2);
  const sum = doubled.reduce((a, b) => a + b, 0);
  console.log("Doubled:", doubled);
  console.log("Sum:", sum);
}
```

This test file should show the language class being preserved as `class="language-javascript"` in the HTML output.
