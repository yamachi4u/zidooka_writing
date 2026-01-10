---
title: "ReferenceError: xxx is not defined が出る原因と対処法【GAS / JavaScript】"
categories:
  - gastips
featured_image: ../images/2025/image copy 21.png
---

JavaScript や Google Apps Script（GAS）を書いていると、次のようなエラーに遭遇することがあります。

```
ReferenceError: xxx is not defined
```

この記事では、このエラーがなぜ起きるのか、そしてどう直せばいいのかを、実際によくあるパターン別に解説します。
GAS・JavaScript 初学者がつまずきやすいポイントを中心に、再発防止まで含めて整理します。

## ReferenceError: xxx is not defined とは何か

このエラーは一言で言うと、

**「参照しようとした変数や関数が、どこにも定義されていない」**

という意味です。

JavaScript では、変数や関数を使う前に、必ず定義する必要があります。

```javascript
console.log(myVar);
```

この時点で `myVar` が定義されていなければ、

```
ReferenceError: myVar is not defined
```

というエラーが発生します。

## よくある原因①：変数を宣言し忘れている

最も基本的なミスです。

### 例：変数を使う前に宣言していない

```javascript
Logger.log(userName);
const userName = "Taro";
```

JavaScript では、変数を使う前に宣言する必要があります。

### 対処法

- 変数を使う前に必ず宣言する
- `const` または `let` を使う

```javascript
const userName = "Taro";
Logger.log(userName);
```

## よくある原因②：スコープの外から参照している

変数には「使える範囲（スコープ）」があります。

### 例：関数の中で定義した変数を外から参照

```javascript
function test() {
  const message = "Hello";
}

Logger.log(message);
```

`message` は `test()` 関数の中でしか使えません。

### 対処法

- 変数のスコープを意識する
- 必要なら関数の外で宣言する

```javascript
const message = "Hello";

function test() {
  Logger.log(message);
}
```

## よくある原因③：タイプミス

シンプルですが、非常に多い原因です。

### 例：変数名のスペルミス

```javascript
const userName = "Taro";
Logger.log(usrName);
```

`userName` を `usrName` と間違えています。

### 対処法

- 変数名は正確に入力する
- コピー＆ペーストを活用する
- エディタの自動補完機能を使う

## よくある原因④：GAS特有の「グローバルオブジェクトの勘違い」

GAS では、ブラウザの JavaScript とは異なるグローバルオブジェクトがあります。

### 例：window や document を使おうとする

```javascript
window.alert("test");
document.getElementById("test");
```

GAS では `window` や `document` は使えません。

### 対処法

- GAS では `Browser.msgBox()` や `HtmlService` を使う
- ブラウザ用のコードをそのまま使わない

```javascript
Browser.msgBox("test");
```

## よくある原因⑤：ライブラリや外部スクリプトの読み込み忘れ

外部のライブラリやスクリプトを使う場合、読み込みが必要です。

### 例：jQuery を使おうとしたが読み込んでいない

```javascript
$('#test').click();
```

`$` (jQuery) が読み込まれていません。

### 対処法

- 必要なライブラリを正しく読み込む
- GAS では「ライブラリ」機能を使う

## よくある原因⑥：非同期処理のタイミング問題

変数が定義される前にアクセスしようとするケースです。

### 例：データ取得前に参照している

```javascript
let data;

fetch(url).then(response => {
  data = response;
});

Logger.log(data);
```

`fetch` が完了する前に `data` を参照しています。

### 対処法

- `await` を使う
- コールバック内で処理する

```javascript
async function getData() {
  const response = await fetch(url);
  Logger.log(response);
}
```

## デバッグの基本チェックリスト

`ReferenceError: xxx is not defined` が出たら、次を順に確認します。

1. 変数や関数は宣言されているか？
2. スコープの範囲内で使っているか？
3. タイプミスはないか？
4. ブラウザ専用のオブジェクトを使っていないか？
5. 必要なライブラリは読み込まれているか？
6. 非同期処理のタイミングは適切か？

この6点だけで、ほとんどのケースは解決できます。

## 再発防止のための書き方

```javascript
// 変数は使う前に宣言
const userName = "Taro";

// スコープを意識する
let globalVar = "global";

function test() {
  let localVar = "local";
  Logger.log(globalVar);
}

// エディタの自動補完を活用
// タイプミスを防ぐ
```

## まとめ

`ReferenceError: xxx is not defined` は、
**「存在しないものを参照しようとしている」**というエラーです。

特に GAS では、

- スコープの理解
- ブラウザとの違い
- ライブラリの読み込み

を意識するだけで、発生頻度は一気に下がります。

エラーメッセージをよく読んで、「どの変数が定義されていないのか？」を確認することが解決への第一歩です。
