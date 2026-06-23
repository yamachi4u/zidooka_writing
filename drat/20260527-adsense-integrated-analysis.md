# AdSense 統合分析レポート (2026-04-29 → 2026-05-26)

## AdSense サマリー

| 指標 | 合計 | 日平均 |
|------|------|--------|
| 収益 | ¥1,132 | ¥40 |
| インプレッション | 15,731 | 561 |
| クリック | 86 | 3 |
| RPM | ¥163 | — |
| CPC | ¥13 | — |

### 日次収益推移（週次平均）

| 週 | 平均収益 | 傾向 |
|----|----------|------|
| 4/29-5/3 | ¥56 | 高 |
| 5/4-5/10 | ¥42 | 低下 |
| 5/11-5/17 | ¥38 | 横ばい |
| 5/18-5/24 | ¥27 | 低下続く |
| 5/25-5/26 | ¥30 | やや回復 |


## GA4 トラフィックサマリー

**総セッション:** 約7,346（28日間）

### チャネル別

| チャネル | セッション | 割合 |
|----------|-----------|------|
| Organic Search | 5,657 | 77% |
| Direct | 1,407 | 19% |
| Unassigned | 141 | 2% |
| Referral | 113 | 2% |

### 検索エンジン別内訳

| エンジン | セッション | 割合 |
|----------|-----------|------|
| Bing | 3,263 | 57.7% |
| Google | 1,927 | 34.1% |
| Yahoo | 194 | 3.4% |
| DuckDuckGo | 159 | 2.8% |
| OpenAI/ChatGPT | 192 | 3.4% |

### 人気ページ

1. `/jp/calendar/2026/05` — 506 sessions（カレンダー）
2. `/jp/calendar/2026/06` — 383 sessions（カレンダー）
3. `/archives/149` — 374 sessions（Lステップ）
4. `/archives/2590` — 271 sessions（edgesuite）
5. `/jp/calendar/2026` — 211 sessions（カレンダー年）
6. `/archives/105` — 202 sessions（PrinceXML）


## GSC 検索クエリ分析

**高CTRクエリ（収益に直結）:**
- `copilot has been working on this problem...` → 55 clicks, 42% CTR, position 1.8
- `"princexml" is required to be installed.` → 38 clicks, 58% CTR, position 2.3
- `edgesuite` → 26 clicks, 26% CTR, position 2.0
- `ポストを読み込めません 垢消し` → 24 clicks, 24% CTR, position 2.7

**低CTR・高インプレッション（改善余地あり）:**
- `something went wrong while generating...` → 677 impressions, 1.8% CTR, position 6.7
- `ポストを読み込めません 特定の人` → 922 impressions, 0.9% CTR, position 6.1
- `status_breakpoint` (tag page) → 522 impressions, 1.1% CTR, position 10.9
- `status_breakpoint` (article) → 338 impressions, 2.1% CTR, position 6.7


## 統合インサイト

### 1. RPMと収益の低下トレンド
- 4月末のRPM ¥458 → 5月下旬 ¥100前後まで下落
- インプレッション数は横ばい〜微増だが、収益は半減
- 原因候補: 季節変動、広告在庫の質低下、競合の増加

### 2. カレンダーページが最大トラフィックだがAdSenseとの相関弱い
- カレンダー月次ページ合計: 約1,100 sessions（全体の15%）
- しかしAdSenseスロット配置はカレンダーページにある
- カレンダーページのエンゲージメント（平均セッション時間311秒）は高い

### 3. BingがGoogleを上回る
- Bing organic: 3,263 sessions vs Google: 1,927 sessions
- エラーメッセージ系コンテンツはBingでのインデックスが強力
- OpenAI/ChatGPTからのトラフィックも無視できない（192 sessions）

### 4. エラーメッセージSEOが主要ドライバー
- GSC上位50クエリの大半はエラーメッセージ（Copilot, ChatGPT, EdgeSuite, PrinceXML）
- これらのCTRは高く（20-60%）、収益への貢献が大きい
- ただし「something went wrong」系はインプレッションが大きくCTRが低い（改善余地）

### 5. ページ単価の高い記事と低い記事の差
- EdgeSuite系ページ: 高インプレッション、高CTR → 収益貢献大
- Lステップ系: セッション多いが低エンゲージメント → RPM低

## アクション候補

1. 低CTR高インプレッションクエリのタイトル最適化（`status_breakpoint`など）
2. カレンダーページ以外の主要ページへのAdSenseスロット追加検討
3. Bing Webmaster Toolsでの追加分析（Bingが最大チャネルのため）
4. RPM低下の原因調査（AdSenseレポートのデバイス/国別分析）
5. エラーメッセージ系の新規記事追加でトラフィック増
