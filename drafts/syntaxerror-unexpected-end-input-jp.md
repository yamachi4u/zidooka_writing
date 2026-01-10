---
title: "SyntaxError: Unexpected end of input が出る原因と対処法【GAS / JavaScript】"
categories:
  - gastips
featured_image: ../images/2025/image copy 21.png
---

JavaScript や Google Apps Script（GAS）を書いていると、次のようなエラーに遭遇することがあります。

```
SyntaxError: Unexpected end of input
```

この記事では、このエラーがなぜ起きるのか、そしてどう直せばいいのかを、実際によくあるパターン別に解説します。
GAS・JavaScript 初学者がつまずきやすいポイントを中心に、再発防止まで含めて整理します。

## SyntaxError: Unexpected end of input とは何か

このエラーは一言で言うと、

**「コードが途中で終わっている（何かが閉じられていない）」**

という意味です。

JavaScript のパーサーは、コードの構造を解析する際に「まだ続きがあるはず」と期待しているのに、コードが終わってしまっていると、このエラーが発生します。

## よくある原因①：中括弧 `{}` の閉じ忘れ

最も多い原因です。

### 例：関数の閉じ括弧が足りない

```javascript
function test() {
  Logger.log("test");
```

`}` が1つ足りません。

### 例：if 文の閉じ括弧が足りない

```javascript
function check() {
  if (true) {
    Logger.log("OK");
}
```

`}` が1つ足りません。

### 対処法

- コードを書く際に、`{` を書いたらすぐに `}` を書く
- エディタのインデント機能を活用する
- 括弧の対応をチェックする機能を使う

```javascript
function test() {
  Logger.log("test");
}
```

## よくある原因②：丸括弧 `()` の閉じ忘れ

関数呼び出しや条件式でよく発生します。

### 例：関数呼び出しの括弧が閉じていない

```javascript
Logger.log("Hello"
```

`)` が足りません。

### 例：条件式の括弧が閉じていない

```javascript
if (x > 10 {
  Logger.log("OK");
}
```

`if` の条件に `)` が足りません。

### 対処法

- 括弧を開いたらすぐに閉じる
- エディタの自動補完を活用する

```javascript
Logger.log("Hello");

if (x > 10) {
  Logger.log("OK");
}
```

## よくある原因③：角括弧 `[]` の閉じ忘れ

配列やオブジェクトのアクセスで発生します。

### 例：配列の定義が閉じていない

```javascript
const list = [1, 2, 3
```

`]` が足りません。

### 対処法

- 配列を定義したらすぐに閉じる

```javascript
const list = [1, 2, 3];
```

## よくある原因④：文字列のクォートが閉じていない

文字列の定義が途中で終わっているケースです。

### 例：ダブルクォートが閉じていない

```javascript
const message = "Hello World
Logger.log(message);
```

閉じる `"` が抜けています。

### 対処法

- 文字列は必ず同じクォートで閉じる
- 複数行の文字列には テンプレートリテラル（バッククォート）を使う

```javascript
const message = "Hello World";
Logger.log(message);

// 複数行の場合
const multiLine = `
  Line 1
  Line 2
`;
```

## よくある原因⑤：ネストが深すぎて対応が見えない

複雑なコードでは、どの括弧がどこで閉じるべきか分からなくなることがあります。

### 例：深いネスト構造

```javascript
function main() {
  if (true) {
    for (let i = 0; i < 10; i++) {
      if (i % 2 === 0) {
        Logger.log(i);
      }
    }
  // ここで } が足りない
}
```

### 対処法

- インデントを正しく揃える
- エディタの折りたたみ機能を使う
- 複雑な処理は関数に分ける

```javascript
function main() {
  if (true) {
    for (let i = 0; i < 10; i++) {
      if (i % 2 === 0) {
        Logger.log(i);
      }
    }
  }
}
```

## よくある原因⑥：コピペでコードが途中で切れている

コードをコピー＆ペーストする際に、最後の部分が欠けていることがあります。

### 対処法

- コピーしたコードは全体を確認する
- 括弧の対応をチェックする

## デバッグの基本チェックリスト

`SyntaxError: Unexpected end of input` が出たら、次を順に確認します。

1. `{` と `}` の数は合っているか？
2. `(` と `)` の数は合っているか？
3. `[` と `]` の数は合っているか？
4. 文字列のクォートは閉じているか？
5. インデントは正しく揃っているか？
6. コピペしたコードは完全か？

この6点だけで、ほとんどのケースは解決できます。

## 再発防止のための書き方

```javascript
// 括弧を開いたらすぐに閉じる
function test() {
  // コードをここに書く
}

// インデントを揃える
if (true) {
  Logger.log("OK");
}

// エディタの自動フォーマット機能を使う
```

## Visual Studio Code や Apps Script エディタの活用

多くのエディタには、括弧の対応を確認する機能があります。

- **括弧のハイライト表示**
- **インデントガイド**
- **自動補完**
- **構文チェック**

これらを活用すると、エラーを未然に防げます。

## まとめ

`SyntaxError: Unexpected end of input` は、
**「何かが閉じられていない」**というエラーです。

特に GAS では、

- 関数の中括弧
- 条件式の丸括弧
- 配列の角括弧

を意識するだけで、発生頻度は一気に下がります。

エラーが出たら、まず「括弧の数が合っているか？」を確認することが解決への第一歩です。エディタの機能を最大限に活用して、快適なコーディングを目指しましょう。
