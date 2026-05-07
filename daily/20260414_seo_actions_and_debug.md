# 2026-04-14 SEO follow-up actions

対象期間: 2026-04-07 から 2026-04-13

## 実施した更新

### `/archives/633`

- タイトルを CTR 改善向けに更新。
- 冒頭近くに「症状別の答え」表を追加。
- `特定の人だけ`、`凍結`、`Android`、`直し方` の検索意図を冒頭で拾う構成にした。

公開確認:
- `https://www.zidooka.com/archives/633`
- 追加見出し `症状別の答え` を確認済み。

### `/archives/2672`

- タイトルを `server error 502/504`、`Stream terminated`、`rate_limited` の文言寄せに更新。
- 冒頭近くに「表示文言別に見るなら」表を追加。
- 502 / 504 / Stream terminated / rate_limited / premium request allowance を個別記事へ誘導。

公開確認:
- `https://www.zidooka.com/archives/2672`
- 追加見出し `表示文言別に見るなら` を確認済み。

### `/archives/185`

- 本文終盤に ChatGPT 関連エラーへの追加導線を追加。
- 既に CTR は改善しているため、大幅な本文変更は避けた。

公開確認:
- `https://www.zidooka.com/archives/185`
- 追加文 `ファイルアップロード以外も同時に不安定なら` を確認済み。

## follow-up レポート

生成済み:
- `daily/seo-followup/seo-followup-20260413.md`
- `daily/seo-followup/seo-followup-20260413.json`

主な結果:
- `/archives/185`: GA4 sessions は 13 -> 17。GSC CTR は 12.41% -> 19.32%。
- `/archives/633`: 表示回数 3246、CTR 0.40%。引き続き改善余地あり。
- `/archives/2672`: GA4 sessions は 35 -> 12。GSC clicks は 16 -> 1。今回、冒頭導線を補強。

## `(not set)` debug 回収

対象期間: 2026-04-07 から 2026-04-13

主要イベント:

| event | count | users |
| --- | ---: | ---: |
| page_view | 1353 | 1057 |
| session_start | 1216 | 1063 |
| zdk_debug_search_boot | 917 | 797 |
| zdk_debug_search_hidden | 95 | 89 |
| zdk_debug_search_bfcache | 1 | 1 |

`landingPagePlusQueryString = (not set)` の主因:

| source / medium | browser | device | sessions |
| --- | --- | --- | ---: |
| bing / organic | Edge | desktop | 53 |
| google / organic | Chrome | desktop | 28 |
| direct / none | Chrome | desktop | 4 |
| google / organic | Safari | desktop | 4 |
| direct / none | Edge | desktop | 3 |

`zdk_debug_search_hidden` の上位:

| page | source / medium | browser | eventCount |
| --- | --- | --- | ---: |
| `/archives/1166` | bing / organic | Edge | 5 |
| `/archives/209` | bing / organic | Edge | 3 |
| `/archives/2590` | google / organic | Chrome | 3 |
| `/archives/2672` | bing / organic | Edge | 3 |
| `/archives/3189` | google / organic | Chrome | 3 |
| `/archives/4006` | google / organic | Chrome | 3 |

## 判断

- `(not set)` は単一記事ではなく、引き続き desktop 検索流入、特に `bing / organic × Edge × desktop` の寄与が最大。
- `zdk_debug_search_hidden` は出ているが、件数は `zdk_debug_search_boot` の約10%程度。
- `bfcache` は 1 件だけなので、現時点では主因ではなさそう。
- 問い合わせ成果は実態として弱いため、GA4 key event 化は今回は保留。

## 次回見ること

- `/archives/633` の GSC CTR が 0.40% から改善するか。
- `/archives/2672` の impressions / clicks が戻るか。
- `(not set)` が Edge / Bing 偏重のままか、hidden の割合が増えるか。
