# 4チャネル統合分析 (2026-04-29 → 2026-05-26)

## データソース

| チャネル | コマンド | 主な指標 |
|---------|----------|---------|
| GA4 (Google Analytics) | `npm run ga4` | セッション、ユーザー、エンゲージメント |
| GSC (Google Search Console) | `npm run gsc` | クリック、インプレッション、CTR、掲載順位 |
| AdSense | `npm run adsense` | 収益、インプレッション、RPM、CPC |
| Bing Webmaster Tools | `npm run bing` | Bingのインプレッション/クリック、クロール状況 |

## トラフィック比較: Bing vs Google

| エンジン | セッション(28d) | シェア |
|---------|----------------|--------|
| Bing | 3,263 | 57.7% |
| Google | 1,927 | 34.1% |
| Yahoo | 194 | 3.4% |
| OpenAI/ChatGPT | 192 | 3.4% |
| DuckDuckGo | 159 | 2.8% |

## 検索クエリの違い

**Google上位:** エラーメッセージ系（Copilot, ChatGPT, EdgeSuite, PrinceXML）
**Bing上位:** カレンダー/暦関連（大安吉日, 一粒万倍日, 2026年5月吉日）＋ Lステップログイン

つまり**コンテンツの役割が完全に異なる**。

## Bing クロール状況（重大）

| 指標 | 値 |
|------|-----|
| 1日あたりクロールページ | 116〜877 |
| 1日あたりクロールエラー | 274〜1,057 |
| エラー率 | 51〜93% |

クロールエラーが異常に高い。原因調査が必要。

## AdSense RPM低下トレンド

| 週 | 平均RPM |
|----|---------|
| 4/29-5/3 | ¥354 |
| 5/4-5/10 | ¥218 |
| 5/11-5/17 | ¥166 |
| 5/18-5/24 | ¥99 |
| 5/25-5/26 | ¥105 |

RPMが4週間で1/4に低下。

## アクションアイデア

### 優先度: 高

1. **Bingクロールエラーの調査と修正**
   - `npm run bing -- --preset crawl-stats` で日次監視
   - Bing Webmaster ToolsのCrawl Issuesを確認し、404/500/リダイレクトループなどを特定
   - エラーページを特定して修正 or noindex

2. **低CTR高インプレッション記事のタイトル最適化**
   - Google: `something went wrong while generating...` (677imp, 1.77%CTR, pos6.7)
   - Google: `status_breakpoint` (338imp, 2.07%CTR, pos6.7)
   - Bing: `2026年05月15日` (429imp, 0.2%CTR) — 日付クエリにヒットするコンテンツがない

3. **RPM低下の原因特定**
   - `npm run ga4 -- --preset countries` で国別トラフィック確認
   - `npm run adsense -- --dimensions DATE,COUNTRY --metrics ESTIMATED_EARNINGS` が必要（API拡張）
   - モバイル/デスクトップ比率の変化を確認

### 優先度: 中

4. **Bing向けカレンダーコンテンツの強化**
   - Bingではカレンダー/暦クエリが強い
   - 「大安カレンダー2027」「六曜早見表」など年単位のコンテンツ追加

5. **AI検索エンジンからのトラフィック取り込み**
   - OpenAI + ChatGPT: 192 sessions（成長中）
   - `llms.txt` / `llms-full.txt` の更新と最適化
   - AIフレンドリーなコンテンツ設計

6. **週次統合レポートの自動化**
   - 4チャネルのデータを1つのスクリプトで取得し、CSV統合
   - 週次変化をトラッキング

### 優先度: 低

7. **Bing × Googleのクエリギャップ記事**
   - Googleで強いエラーメッセージ記事をBing向けに最適化
   - Bingで強いカレンダー記事をGoogle向けに強化

8. **OpenAIリファラルの活用**
   - OpenAI経由のセッションは平均4.7 pageviewsと高い
   - AIチャットボットからの参照を意識したコンテンツ改善

## クロスチャネル分析で使えるコマンド

```powershell
# 一括取得（レポート作成用）
npm run ga4 -- --preset overview
npm run ga4 -- --preset acquisition
npm run gsc -- --preset top-queries --limit 30 --start-date YYYY-MM-DD --end-date YYYY-MM-DD
npm run adsense
npm run bing
npm run bing -- --preset top-queries --limit 20
npm run bing -- --preset crawl-stats
```
