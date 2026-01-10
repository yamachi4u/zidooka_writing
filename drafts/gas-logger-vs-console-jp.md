---
title: "Logger.log と console.log の違いとは？GAS実務ではどちらを使うべきか"
date: 2025-12-23
thumbnail: images/2025/gas-logger-vs-console.png
categories: 
  - GAS Tips
---

Google Apps Script（GAS）でデバッグをしていると、
`Logger.log` と `console.log`、結局どっちを使えばいいの？
という疑問に一度はぶつかります。

この記事では、違いと実務での最適解だけをシンプルにまとめます。

## 結論：実務では console.log を使う

先に結論です。

**新しく書く GAS のコードでは `console.log` を使う**
`Logger.log` は基本的に「古い書き方」です。

理由はあとで説明しますが、
迷ったら `console.log` 一択で問題ありません。

## Logger.log と console.log の違い

### 機能的な違い一覧

| 項目 | Logger.log | console.log |
| --- | --- | --- |
| 種類 | GAS 独自API | JavaScript標準 |
| 登場時期 | 古い | 比較的新しい |
| ログの表示 | 遅め | 速い |
| オブジェクト表示 | 弱い | 強い |
| 配列・JSON確認 | 不便 | 見やすい |
| 将来性 | 低い | 高い |

ポイントはひとつだけです。

*   `console.log` は「普通の JavaScript」
*   `Logger.log` は「GAS専用の昔の仕組み」

### 実際の表示の違い

#### Logger.log の例

```javascript
Logger.log(obj);
```

*   オブジェクトが `[object Object]` になることが多い
*   中身を確認するには `JSON.stringify` が必要

#### console.log の例

```javascript
console.log(obj);
```

*   オブジェクト構造がそのまま見える
*   配列・JSON の確認が一瞬でできる

👉 **デバッグ効率がまったく違う**

## 実務ではどっちを使うべき？

### 普段の開発・検証

```javascript
console.log(data);
```

これで十分です。

*   見やすい
*   速い
*   JSの知識がそのまま使える

### Logger.log を使う場面（ほぼ例外）

正直、かなり限定的です。

*   古い GAS コードを保守するとき
*   既存コードに合わせる必要があるとき
*   文字列を一行出すだけの簡易ログ

新規実装で積極的に使う理由はほぼありません。

## トリガー実行・本番運用での注意点

ここは重要です。

`Logger.log` も `console.log` も
**トリガー実行では即時に見えない**

どちらも「後からログを確認」する形になります。

そのため、実務では次のように使います。

```javascript
console.error(e);
```

または

```javascript
console.log({
  status: "error",
  message: e.message
});
```

👉 **構造化ログにすると後で追いやすい**

## よくある誤解

❌ **Logger.log のほうが安全？**
→ 関係ありません

❌ **console.log は GAS だと不安定？**
→ 公式にサポートされています

❌ **Logger.log は推奨されている？**
→ いいえ。単に昔からあるだけです

## まとめ（実務向け超短縮）

*   新しく書くコード → `console.log`
*   `Logger.log` → レガシー対応用
*   デバッグ効率・将来性は `console.log` が圧勝

GAS でエラーや挙動を追うなら、
「ログをどう出すか」だけで作業時間がかなり変わります。

`console.log` に統一しておくと、
JS・Node・GAS を横断して同じ感覚でデバッグできるのでおすすめです。
