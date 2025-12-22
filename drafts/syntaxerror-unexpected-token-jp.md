---
title: "SyntaxError: Unexpected token が出る原因と対処法【GAS / JavaScript】"
categories:
  - gastips
featured_image: ../images/image copy 21.png
---

JavaScript や Google Apps Script（GAS）を書いていると、次のようなエラーに遭遇することがあります。

```
SyntaxError: Unexpected token
```

この記事では、このエラーがなぜ起きるのか、そしてどう直せばいいのかを、実際によくあるパターン別に解説します。
GAS・JavaScript 初学者がつまずきやすいポイントを中心に、再発防止まで含めて整理します。

## SyntaxError: Unexpected token とは何か

このエラーは一言で言うと、

**「文法的に予期しない記号や文字が見つかった」**

という意味です。

JavaScript のパーサーは、コードを実行する前に構文をチェックします。その際、予想外の記号（トークン）が現れると、このエラーが発生します。

```
SyntaxError: Unexpected token '{'
```

このように、具体的にどの記号が問題かが表示されることもあります。

## よくある原因①：括弧やカッコの閉じ忘れ

最も多い原因のひとつです。

### 例：中括弧が足りない

```javascript
function test() {
  Logger.log("test");
```

`}` が足りません。

### 例：丸括弧が閉じていない

```javascript
Logger.log("Hello"
```

`)` が足りません。

### 対処法

- エディタのシンタックスハイライトを活用する
- 括弧の対応を確認する機能を使う
- コードを書いたらすぐに括弧を閉じる習慣をつける

```javascript
function test() {
  Logger.log("test");
}
```

## よくある原因②：カンマやセミコロンの間違い

### 例：配列の最後に余計なカンマ

```javascript
const list = [1, 2, 3,];
```

最後のカンマが問題になる場合があります（古いブラウザやGASの一部環境）。

### 例：オブジェクトのプロパティ区切りミス

```javascript
const user = {
  name: "Taro"
  age: 20
};
```

`name` の後にカンマが必要です。

### 対処法

- オブジェクトや配列の区切りを確認する
- カンマを忘れない

```javascript
const user = {
  name: "Taro",
  age: 20
};
```

## よくある原因③：予約語を変数名に使っている

JavaScript には使用できない予約語があります。

### 例：予約語を変数名にする

```javascript
const function = "test";
const class = "myClass";
```

`function` や `class` は予約語なので使えません。

### 対処法

- 予約語を避ける
- 代わりに `func` や `className` を使う

```javascript
const func = "test";
const className = "myClass";
```

## よくある原因④：文字列のクォートが閉じていない

### 例：文字列が途中で終わっている

```javascript
const message = "Hello World;
Logger.log(message);
```

閉じる `"` が抜けています。

### 対処法

- 文字列は必ず同じクォートで閉じる
- エディタの自動補完を使う

```javascript
const message = "Hello World";
Logger.log(message);
```

## よくある原因⑤：コメントアウトの間違い

### 例：複数行コメントが閉じていない

```javascript
/* This is a comment
Logger.log("test");
```

`*/` で閉じる必要があります。

### 対処法

- コメントは正しい構文で書く
- 複数行コメントは `/* ... */`
- 単一行コメントは `//`

```javascript
/* This is a comment */
Logger.log("test");
```

## よくある原因⑥：JSON の構文ミス

GAS では JSON を扱うことが多く、構文エラーが起きやすいです。

### 例：JSON のキーにクォートがない

```javascript
const data = {
  name: "Taro"
};
```

これは JavaScript のオブジェクトであり、JSON ではありません。JSON では：

```javascript
const jsonString = '{"name": "Taro"}';
```

### 対処法

- JSON.parse() を使う場合は、正しい JSON 形式で書く
- キーも文字列で囲む

```javascript
const data = JSON.parse('{"name": "Taro"}');
```

## デバッグの基本チェックリスト

`SyntaxError: Unexpected token` が出たら、次を順に確認します。

1. 括弧やカッコは正しく閉じているか？
2. カンマやセミコロンは正しく配置されているか？
3. 予約語を変数名に使っていないか？
4. 文字列のクォートは閉じているか？
5. コメントの構文は正しいか？
6. JSON の構文は正しいか？

この6点だけで、ほとんどのケースは解決できます。

## 再発防止のための書き方

```javascript
// エディタのシンタックスハイライトを活用
// 自動フォーマット機能を使う

// 括弧はすぐに閉じる
function test() {
  // コードをここに書く
}

// カンマを忘れない
const user = {
  name: "Taro",
  age: 20
};
```

## まとめ

`SyntaxError: Unexpected token` は、
**「文法的に間違った記号がある」**というエラーです。

特に GAS では、

- 括弧やカッコの対応
- JSON の構文
- 文字列のクォート

を意識するだけで、発生頻度は一気に下がります。

エラーメッセージには「どの記号が問題か」が表示されるので、その周辺のコードを注意深く確認することが解決への近道です。
