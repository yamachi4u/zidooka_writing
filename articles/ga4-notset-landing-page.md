---
title: "GA4でランディングページが(not set)になる原因と6つの対策"
slug: ga4-notset-landing-page
status: publish
categories:
  - Google / GA4 / Apps Script Errors
  - SEO
tags:
  - GA4
  - Google Analytics
  - landing page
  - not set
date: 2026-06-08
---

Google Analytics 4（GA4）のレポートで、ランディングページが `(not set)` と表示されることがある。セッションはカウントされているのに「どのページから来たのか分からない」状態だ。

この記事では `(not set)` が発生する原因を6つに分類し、それぞれの確認方法と対策をまとめる。

## 原因1: ボット・クローラートラフィック

GA4はボットトラフィックを完全には除外できない。以下のようなパラメータを含むURLでセッションが計測されている場合、それが原因である可能性が高い:

```
/simple-metronome?use_xbridge3=true&loader_name=forest&need_sec_link=1&sec_link_scene=im&theme=light
/csv-to-ics?use_xbridge3=true&loader_name=forest&need_sec_link=1&sec_link_scene=im&theme=light
```

このようなURLは、SEOクローラー・プロキシサービス・自動テストツールに共通する特徴を持つ。JavaScriptを部分的に実行するため `session_start` は発火するが、`page_view` が完了せず、結果として `(not set)` となる。

**対策:**
- GA4管理画面 → データストリーム → Googleタグ → 「ボットフィルタリングを有効にする」をオン
- サーバーサイドで既知のクローラーユーザーエージェントをブロック
- googleAnalyticsの `gtag('config', 'G-MEASUREMENT_ID', { send_page_view: true })` を確実に設定

## 原因2: カスタムイベントに `page_location` が欠如している

GA4ではランディングページの判定に `page_view` イベントの `page_location` パラメータを使う。カスタムイベント（例: `tool_action`、`ad_impression`）を `page_location` なしで送信すると、そのセッションで `page_view` が正常に発火しなかった場合に `(not set)` となる。

**対策:**
- カスタムイベントを送信する際は `page_location` パラメータを含める:

```js
gtag('event', 'custom_event', {
  page_location: window.location.href,
});
```

- 特に `tool_action` やクリック系のイベントは注意が必要。ユーザーが操作したタイミングで `page_view` より先にカスタムイベントが飛ぶ可能性がある。

## 原因3: Service Worker の干渉

Service Worker がページをキャッシュから配信する際、外部スクリプト（gtag.js）の読み込みが遅延または失敗することがある。その結果、`session_start` は記録されるが `page_view` が発火しないセッションが生まれる。

**対策:**
- Service Worker の `IGNORE_ORIGINS` に `www.googletagmanager.com` と `www.google-analytics.com` が含まれていることを確認
- オフラインキャッシュを優先する戦略の場合、gtag.js の読み込みを `navigator.onLine` で条件分岐する

## 原因4: SNSクローラー・スクレイパーによる不正URL

以下のような、URLに日本語テキストが連結された形のランディングページが確認されることがある:

```
/jp/calendar/taian）も参考になります
/jp/calendar）にアクセスします
```

これらはSNS（LINE、X/Twitter）やスクレイパーボットが、文章中からURLを正しく抽出できずに誤った値を送信したもの。これらのセッションはほぼ必ず `page_view` が0になる。

**対策:**
- 直接の対策は困難。ただし、GA4のフィルタで `page_view = 0` のセッションを除外することでレポートをクリーンにできる
- URLに全角文字や日本語テキストが連結されたものをレポートから除外する正規表現フィルタを設定する

## 原因5: gtag設定パラメータの誤用

GA4の設定で、存在しないパラメータを `gtag('config', ...)` に渡してもエラーにはならないが、無視される。例えば:

```js
gtag('config', 'G-MEASUREMENT_ID', {
  enhanced_measurement: true,  // ❌ これは無効なパラメータ
});
```

`enhanced_measurement` はプロパティ設定画面で有効にするものであり、gtagに渡すパラメータではない。この設定ミス自体はエラーを出さないため気づかれにくい。

**対策:**
- GA4の設定は管理画面の「データストリーム」→「拡張計測機能」で行う
- gtagの `config` コマンドには有効なパラメータのみを渡す

## 原因6: Measurement Protocol / サーバーサイド送信の設定ミス

Measurement Protocol を使ってサーバーサイドからGA4にイベントを送信する場合、`page_location` や `page_title` を含めないと `(not set)` になる。また、必須パラメータがないとセッション自体が不完全な状態で記録される。

**対策:**
- Measurement Protocol を使用する場合は `page_location` を常に含める
- テスト段階では `debug_mode: true` を付けてGA4のDebugViewで確認する

## 確認手順

1. **GA4レポートで該当セッションを特定する**
   - 「集客」→「ランディングページ」で `(not set)` の行をクリック
   - セカンダリディメンションに「ページタイトル」を追加して傾向を確認

2. **URLパラメータのパターンを分析する**
   - `(not set)` のセッションで共通するURLパラメータを探す
   - `xbridge3`、`loader_name`、`need_sec_link` などが見つかればボットトラフィックの可能性が高い

3. **Service Workerの動作を確認する**
   - Chrome DevTools → Application → Service Workers
   - キャッシュ戦略がgtag.jsの読み込みを妨げていないか確認

4. **カスタムイベントの実装を見直す**
   - サイト内で `gtag('event', ...)` を呼び出している箇所をすべて洗い出す
   - 各イベントに `page_location` が含まれているか確認

:::conclusion
`(not set)` の原因は一つではなく、複数の要因が重なっていることが多い。ボットトラフィックの除外と `page_location` の徹底が最も効果的な対策である。完全になくすことは難しいが、原因を特定して個別に対応することで、レポートの品質は確実に改善する。
:::

---

# Why GA4 Shows "(not set)" as a Landing Page — 6 Causes and Fixes

In Google Analytics 4 reports, landing pages sometimes display as `(not set)`. Sessions are counted, but the entry page is unknown.

This article categorizes the causes into six patterns with diagnosis steps and solutions.

## Cause 1: Bot / Crawler Traffic

GA4 cannot fully exclude bot traffic. If you see sessions with URL parameters like `xbridge3=true`, `loader_name=forest`, `need_sec_link=1`, bots are likely the cause:

```
/simple-metronome?use_xbridge3=true&loader_name=forest&need_sec_link=1&sec_link_scene=im&theme=light
```

These URLs are characteristic of SEO crawlers, proxy services, and automated testing tools. They partially execute JavaScript, firing `session_start` but not completing `page_view`.

**Fix:**
- Enable bot filtering in GA4 admin: Data Streams → Google Tag → "Enable bot filtering"
- Block known crawler user agents server-side
- Ensure `gtag('config', 'G-MEASUREMENT_ID', { send_page_view: true })` is properly set

## Cause 2: Missing `page_location` on Custom Events

GA4 determines landing pages from the `page_location` parameter of the `page_view` event. If custom events (e.g., `tool_action`, `ad_impression`) fire without `page_location`, and `page_view` doesn't fire in that session, the landing page becomes `(not set)`.

**Fix:**
- Always include `page_location` when sending custom events:

```js
gtag('event', 'custom_event', {
  page_location: window.location.href,
});
```

- Pay special attention to click-based events that could fire before `page_view` completes.

## Cause 3: Service Worker Interference

When a Service Worker serves pages from cache, external scripts like gtag.js may load late or fail. This creates sessions where `session_start` fires but `page_view` does not.

**Fix:**
- Confirm that `IGNORE_ORIGINS` includes `www.googletagmanager.com` and `www.google-analytics.com`
- Consider checking `navigator.onLine` before loading gtag.js in offline-first strategies

## Cause 4: Social Crawlers / Scrapers With Malformed URLs

Some sessions show landing pages with Japanese text concatenated to URLs:

```
/jp/calendar/taian）も参考になります
```

These come from SNS crawlers (LINE, X/Twitter) or scrapers that fail to extract URLs correctly from text. These sessions almost always have zero page views.

**Fix:**
- Apply a GA4 filter to exclude sessions with `page_view = 0`
- Add a regex filter to exclude landing pages containing non-URL characters

## Cause 5: Invalid gtag Config Parameters

Passing unsupported parameters to `gtag('config', ...)` does not cause errors but is silently ignored:

```js
gtag('config', 'G-MEASUREMENT_ID', {
  enhanced_measurement: true,  // ❌ Invalid parameter
});
```

`enhanced_measurement` is a property-level setting configured in the GA4 admin, not a gtag parameter.

**Fix:**
- Configure Enhanced Measurement in GA4 admin: Data Streams → your web stream → Enhanced Measurement toggle
- Only pass valid parameters to `gtag('config', ...)`

## Cause 6: Measurement Protocol Misconfiguration

When sending events server-side via Measurement Protocol without `page_location` or `page_title`, landing pages appear as `(not set)`.

**Fix:**
- Always include `page_location` in Measurement Protocol payloads
- Test with `debug_mode: true` and verify in GA4 DebugView

## Diagnosis Steps

1. **Identify affected sessions in GA4**
   - Go to Acquisition → Landing Page, click the `(not set)` row
   - Add "Page Title" as a secondary dimension

2. **Analyze URL parameter patterns**
   - Look for common parameters among `(not set)` sessions
   - Parameters like `xbridge3`, `loader_name`, `need_sec_link` indicate bot traffic

3. **Inspect Service Worker behavior**
   - Chrome DevTools → Application → Service Workers
   - Verify caching strategy does not block gtag.js

4. **Audit custom event implementations**
   - Find all `gtag('event', ...)` calls in your codebase
   - Check each for the `page_location` parameter

:::conclusion
The `(not set)` landing page issue rarely has a single cause. Excluding bot traffic and consistently including `page_location` are the most effective measures. While eliminating it completely is difficult, identifying and addressing each cause will significantly improve report quality.
:::
