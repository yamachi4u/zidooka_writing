# GA4 `(not set)` 調査メモ

調査日: 2026-03-26 JST

対象期間: 2026-02-26 から 2026-03-25

対象プロパティ: `344037190`

関連TODO:
- [20260326_analytics_todo.md](C:/Users/user/Documents/zidooka_writing/daily/20260326_analytics_todo.md)

## 結論

- `landing page = (not set)` は、単一ページの不具合ではありません。
- 複数の記事URLにまたがって発生しています。
- 共通しているのは、`desktop` の検索流入、とくに `bing / organic` と `google / organic` です。
- イベント内訳を見ると、`session_start` と `user_engagement` はあるのに、`page_view` がありません。
- そのため、GA4 がセッションのランディングページを決められず、`(not set)` になっている可能性が高いです。

## 数字

- `(not set)` セッション数: `465`
- 内訳:
  - `bing / organic`: `255` (`54.8%`)
  - `google / organic`: `139` (`29.9%`)
  - `(direct) / (none)`: `21`
  - `duckduckgo / organic`: `11`

## 発生条件

- OS / ブラウザの山:
  - `Windows × Edge × desktop × bing / organic`: `248 sessions`
  - `Windows × Chrome × desktop × google / organic`: `80 sessions`
  - `Macintosh × Chrome × desktop × google / organic`: `29 sessions`
- かなり `desktop` 偏重です。
- `mobile` はごく少量です。

## イベント内訳

`landingPagePlusQueryString = (not set)` で分解すると、主なイベントは次の通りです。

- `session_start`
- `user_engagement`
- 一部だけ `scroll` / `click`
- `page_view` は確認できず

つまり、`(not set)` は「ページURLが不明」なのではなく、「セッションは始まっているが、ランディングページ判定に必要な `page_view` が欠けている」状態に見えます。

## どのURLで起きているか

`session_start` / `user_engagement` 側の `pagePathPlusQueryString` を見ると、対象は1ページではありません。

例:

- `/archives/209`
- `/archives/105`
- `/archives/1166`
- `/archives/3506`
- `/archives/185`
- `/archives/2590`
- `/archives/2672`
- `/archives/621`

上位記事に散っているので、個別記事のHTML崩れより、計測方式か流入元ブラウザ側の挙動を疑う方が自然です。

## 実装確認

実サイトの HTML では、サイト全体は `GTM` ではなく `gtag` の直貼りでした。

```html
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VNF3D5QY6E"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-VNF3D5QY6E');
</script>
```

この形なら通常は `page_view` も飛ぶはずなので、タグ自体が全面的に壊れている感じではありません。

## いちばん近い仮説

いま一番近い理解はこれです。

- 検索結果や外部参照元からの `desktop` 流入の一部で、ページの読み込み途中または特殊な表示状態で `session_start` だけ立っている
- その一方で `page_view` が残らず、GA4 上で `landing page = (not set)` になる

候補としては次の2つです。

1. ブラウザや検索エンジン側のプレビュー / 先読み / 特殊表示に近い挙動
2. `page_view` だけが欠落する一時的な計測取りこぼし

## 現時点での判断

- `not set` の主因は「謎の1ページ」ではない
- `desktop 検索流入` 由来の計測欠けが主因
- 特に `bing / organic × Edge` の寄与が大きい

## 次にやるなら

1. `page_view` 欠落の確認用に、短期間だけデバッグイベントを入れる
   - `document.visibilityState`
   - `document.referrer`
   - `navigator.userAgent`
   - `location.pathname`
2. `page_view` の手動二重送信はまだ入れない
   - 今の `gtag('config')` と競合して二重計測の危険があるため
3. レポート上は当面、`landing page = (not set)` を別枠管理する

## 保留タスク

### 1. debug event の回収

- 実装済みイベント:
  - `zdk_debug_search_boot`
  - `zdk_debug_search_hidden`
  - `zdk_debug_search_prerender`
  - `zdk_debug_search_activate`
  - `zdk_debug_search_bfcache`
- 2026-03-27 以降に GA4 で回収して確認する
- 見たいポイント:
  - `zdk_debug_search_hidden` が出ているか
  - `zdk_debug_search_prerender` / `zdk_debug_search_activate` が出ているか
  - `page_referrer` が `bing / google` 寄りか
  - `page_location` が `(not set)` 上位記事と一致するか

### 2. key event 整理

- 現状、GA4 の `keyEvents` は実務判断に使えるほど整っていない
- 次に整理したい候補:
  - 相談フォーム送信
  - メールクリック
  - 主要CTAクリック
- 目的:
  - どの記事が読まれたかだけでなく、どの記事が相談や問い合わせにつながったかを見る
  - GA4 の改善判断を `PV / sessions` だけから脱却する
