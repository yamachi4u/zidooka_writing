---
title: "TypeError: xxx is not a function が出る原因と対処法【GAS / JavaScript】"
categories:
  - gastips
featured_image: ../images/image copy 21.png
---

JavaScript や Google Apps Script（GAS）を書いていると、次のようなエラーに遭遇することがあります。

```
TypeError: xxx is not a function
```

この記事では、このエラーがなぜ起きるのか、そしてどう直せばいいのかを、実際によくあるパターン別に解説します。
GAS・JavaScript 初学者がつまずきやすいポイントを中心に、再発防止まで含めて整理します。

## TypeError: xxx is not a function とは何か

このエラーは一言で言うと、

**「関数だと思って呼び出したものが、実際には関数ではなかった」**

という意味です。

JavaScript では、次のような書き方をすると関数呼び出しになります。

```javascript
xxx();
```

しかし `xxx` が関数ではない値（文字列・数値・配列・オブジェクト・undefined など）だった場合、このエラーが発生します。

## よくある原因①：変数で関数を上書きしている

最も多い原因のひとつです。

### 例：関数名と同じ名前の変数を定義してしまう

```javascript
function getData() {
  return "data";
}

getData = "hello";
getData();
```

この場合、`getData` は途中から文字列になっています。
そのため `getData()` を実行すると、

```
TypeError: getData is not a function
```

になります。

### 対処法

- 関数名と変数名を絶対に被らせない
- `const` / `let` を使ってスコープを意識する

```javascript
const result = getData();
```

## よくある原因②：オブジェクトのプロパティを関数だと勘違いしている

### 例：関数だと思ったら値だった

```javascript
const user = {
  name: "Taro",
  age: 20
};

user.name();
```

`name` は文字列なので、関数として呼べません。

### 対処法

- `()` を付ける前に「本当に関数か？」を疑う
- ログで型を確認する

```javascript
Logger.log(typeof user.name);
```

## よくある原因③：配列やオブジェクトを関数として呼んでいる

### 例：配列を関数のように呼んでいる

```javascript
const list = [1, 2, 3];
list();
```

### 例：オブジェクトを関数として呼んでいる

```javascript
const data = {};
data();
```

どちらも典型的な `xxx is not a function` の原因です。

### 対処法

- 配列・オブジェクトには `()` を付けない
- メソッドを呼びたい場合は `.methodName()` にする

```javascript
list.push(4);
```

## よくある原因④：GAS特有の「返り値の勘違い」

GAS では返り値が関数ではないケースが多くあります。

### 例：getValues() の結果を関数として呼ぶ

```javascript
const values = sheet.getRange("A1:A5").getValues();
values();
```

`getValues()` の返り値は配列です。

### 対処法

- GAS の公式リファレンスで返り値の型を確認する
- `typeof` や `Array.isArray()` で確認する

```javascript
Logger.log(Array.isArray(values));
```

## よくある原因⑤：関数が未定義のまま実行されている

### 例：想定していた関数が存在しない

```javascript
doSomething();
```

実は `doSomething` がどこにも定義されていない、または条件分岐で代入されていないケースです。

### 対処法

- 実行前にログを入れる
- 関数が本当に定義されているか確認する

```javascript
Logger.log(typeof doSomething);
```

## デバッグの基本チェックリスト

`TypeError: xxx is not a function` が出たら、次を順に確認します。

1. `xxx` は本当に関数か？
2. 途中で変数として上書きしていないか？
3. 配列・オブジェクト・文字列を呼んでいないか？
4. 返り値の型を勘違いしていないか？
5. スコープの外で定義されていないか？

この5点だけで、ほとんどのケースは解決できます。

## 再発防止のための書き方

```javascript
// 関数名と変数名を分ける
const / let を使う

// 返り値の型を意識する
const result = getData();

// ログで型確認を習慣化する
Logger.log(typeof fn);

// ガード句を使う
if (typeof fn === "function") {
  fn();
}
```

こうしたガードを書くのも有効です。

## まとめ

`TypeError: xxx is not a function` は、
**「関数だと思い込んでいること」**が原因で起きるエラーです。

特に GAS では、

- 返り値の型
- オブジェクト構造
- 変数の上書き

を意識するだけで、発生頻度は一気に下がります。

エラー文そのものに惑わされず、「今、呼んでいる `xxx` は何者か？」を冷静に確認することが解決への近道です。
