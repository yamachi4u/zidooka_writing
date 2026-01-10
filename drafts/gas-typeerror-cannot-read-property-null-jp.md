---
title: "GASの「Cannot read property 'xxx' of null」エラー — nullとundefinedの違いから解決まで"
slug: "gas-typeerror-cannot-read-property-null-jp"
status: "publish"
categories: 
  - "GAS Tips"
  - "エラー全集"
tags: 
  - "GAStips"
  - "Google Apps Script"
  - "TypeError"
  - "null"
featured_image: "../images/2025/image copy 19.png"
---

# GASの「Cannot read property 'xxx' of null」エラー — nullとundefinedの違いから解決まで

![GASのnullエラーガイド](../images/2025/image%20copy%2019.png)

このエラーは、Google Apps Script (GAS) で非常によく見かける実行時エラーの1つです：**TypeError: Cannot read property 'xxx' of null**

前回のundefinedエラーに見た目は似ていますが、原因とデバッグアプローチは異なります。この違いを理解することが、安定したGAS開発には不可欠です。

---

## 「TypeError: Cannot read property 'xxx' of null」ってどういう意味？

このエラーは、あなたのコードが **null という値にプロパティをアクセス**しようとするときに発生します。JavaScriptでは `null` は「意図的に値がない」を意味し、`undefined` は「割り当てられていない、または見つからない」を意味します。GASでは、APIやサービスがリソースは概念的に存在するが値がない場面で `null` を返すことが多くあります。

---

## GASでこのエラーを引き起こすコード例

### スプレッドシートのセルが空の場合

```javascript
function sampleNullSheet() {
  const sheet = SpreadsheetApp.getActiveSheet();
  const cell = sheet.getRange("A1").getValue();
  Logger.log(cell.value);
}
```

セルが空の場合、`getValue()` は `null` を返します。`cell.value` にアクセスしようとするとエラーが発生します。

---

### サポートされていない環境での getActiveUser()

```javascript
function sampleNullUser() {
  const user = Session.getActiveUser();
  Logger.log(user.getEmail());
}
```

コンシューマーアカウントや特定の実行環境では、`getActiveUser()` が `null` を返すことがあり、エラーが発生します。

---

### HtmlServiceで見つからない要素

```javascript
function sampleHtml() {
  const element = null;
  Logger.log(element.id);
}
```

予期されたDOM要素が存在しないか読み込みに失敗した場合に発生することが多くあります。

---

### DriveApp でファイルが見つからない

```javascript
function sampleDrive() {
  const files = DriveApp.getFilesByName("nonexistent.txt");
  const file = files.hasNext() ? files.next() : null;
  Logger.log(file.getId());
}
```

ファイルがマッチしない場合、`file` は `null` になり、そのプロパティにアクセスするとエラーが発生します。

---

## GAS がよく null を返す理由

GASのサービスは、多くの場面で意図的に `null` を返します：

* スプレッドシートの空のセル
* 見つからないドライブファイルやフォルダ
* 利用できないユーザーコンテキスト
* オプション設定値

この設計は、開発者が「値がない」シナリオを明示的に処理することを強制します。

---

## このエラーを防ぐ方法

### 明示的なnullチェック

```javascript
if (file !== null) {
  Logger.log(file.getId());
}
```

---

### 防御的なデフォルト値

```javascript
const safeName = file ? file.getName() : "";
```

---

### オプショナルチェーニング（対応している場合）

```javascript
Logger.log(file?.getId());
```

オプショナルチェーニングは `null` を有効なオブジェクトに変換するのではなく、実行時エラーを防ぐだけであることに注意してください。

---

## null と undefined の主な違い

| 値 | 意味 | GASでの典型的な出典 |
|----|------|-------------------|
| `undefined` | 割り当てられていない / 見つからない | 配列、オブジェクト、`find()` |
| `null` | 意図的に値がない | スプレッドシート、ドライブ、Session API |

この違いを理解することで、適切なガード戦略を選択できます。

---

## 結論

「TypeError: Cannot read property 'xxx' of null」は、あなたのスクリプトがGASサービスから明示的な「値がない」を受け取り、それを有効なオブジェクトとして扱おうとしたことを示しています。

`null` がどこから来るのかを認識し、適切なチェックを追加することで、Google Apps Scriptの本番環境での障害を大幅に減らすことができます。
