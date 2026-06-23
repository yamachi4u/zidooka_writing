# 中期計画 (2026-05-27)

## 現在地

| 指標 | 値(28d) | 状態 |
|------|---------|------|
| AdSense収益 | ¥1,133 | 低（RPM半減中） |
| 月間セッション | ~7,350 | 安定 |
| Bingシェア | 57.7% | 主チャネル |
| Googleシェア | 34.1% | エラーメッセージが主 |
| AI検索 | 192 sessions | 成長中 |
| Desktop fill rate | 64.89% | 低下傾向 |
| Bingクロールエラー | 56.2% | 異常 |

---

## Phase 1: 基盤修復（Week 22-23: 5/27〜6/9）

### 1.1 Bingクロールエラー調査・修正
- [ ] `npm run bing -- --preset crawl-stats` でエラー傾向確認
- [ ] Bing Webmaster Tools UIで Crawl Issues の詳細確認
- [ ] 4xx/5xxページの修正またはリダイレクト設定
- **目標**: クロールエラー率を30%以下に

### 1.2 低CTR記事タイトル最適化
- [ ] `/archives/121` `something went wrong while generating...` (677imp, CTR1.77%, pos6.7)
  → タイトルに `if this issue persists please contact us through our help center at help.openai.com` を追加
- [ ] `/archives/4219` `status_breakpoint` (338imp, CTR2.07%, pos6.7)
  → タイトルに `エラー コード: status_breakpoint` のキーワードを含める
- [ ] `/archives/4287` `ポストを読み込めません 特定の人` (922imp, CTR0.98%, pos6.1)
  → 検索意図に合ったタイトルに修正（垢消し・アカウント削除の文脈を強化）
- **目標**: 各記事のCTRを5%以上に

### 1.3 監視体制の自動化
- [x] `npm run weekly` で週次レポート自動生成
- [ ] 週次レポートの定期実行（スケジュールまたは手動で毎週月曜）

---

## Phase 2: トラフィック拡大（Week 24-27: 6/10〜7/7）

### 2.1 エラーメッセージSEOのギャップ埋め
- [ ] GSCで発見されていない新しいエラーメッセージを調査（Bingも含む）
- [ ] 未カバーのエラーメッセージを記事化（月4本目標）
- ターゲット候補:
  - `npm ERR!` 系（GSC data-driven）
  - `VSCode` 新エラー
  - `Cursor` エラー
  - `GitHub Copilot` 新エラーメッセージ

### 2.2 Bing向けカレンダーコンテンツ拡充
- [ ] 「2027年吉日カレンダー」記事
- [ ] 「六曜早見表」「大安早見表」
- [ ] 季節ごとの暦注まとめ
- **目標**: BingカレンダークエリのCTR改善（現在0.2-3%）

### 2.3 AI検索最適化
- [ ] `llms.txt` / `llms-full.txt` 強化（最新記事の追加）
- [ ] OpenAIからのトラフィック増加施策（構造化データの最適化）
- [ ] AIチャットボットが参照しやすい記事フォーマットの検討

---

## Phase 3: 収益改善（Week 28-33: 7/8〜8/18）

### 3.1 Desktop Fill Rate改善
- [ ] 高トラフィックページの広告位置ABテスト
- [ ] ビネット広告（自動差し込み）の有効化テスト
- [ ] アンカー広告の導入検討
- **目標**: Desktop fill rateを75%以上に（現在64.89%）

### 3.2 収益モニタリング強化
- [x] RPM（デバイス別）の週次監視
- [ ] アラート: RPMが前週比-20%超で通知
- [ ] 月次で収益トレンドレポートをzidookaにアップ

### 3.3 広告スロット追加
- [ ] 記事ビュー上位20ページの広告配置を棚卸し
- [ ] カレンダーページ以外へのAdSenseスロット追加検討
- [ ] エラーメッセージ系記事（CTR高）への広告最適化

---

## Phase 4: スケール（Week 34-46: 8/19〜11/15）

### 4.1 tools.zidooka.com 拡充
- [ ] 新規無料ツール追加（月1ツール目標）
- [ ] 既存ツールのSEO改善（メタデータ、hreflang）
- [ ] tools ↔ blog 相互リンク戦略

### 4.2 収益源の多様化
- [ ] アフィリエイト記事のパイロット（3本）
- [ ] KDP書籍とのクロスプロモーション
- [ ] メルマガ/サブスクリプションの検討

### 4.3 オペレーションの自動化
- [ ] 記事投稿→IndexNow自動通知のパイプライン完成
- [ ] SEOフォローアップの完全自動化
- [ ] データ分析ダッシュボード（4チャネル統合）

---

## 目標KPI

| KPI | 現在 | 6ヶ月目標 |
|-----|------|----------|
| 月間AdSense収益 | ¥1,133 | ¥3,000+ |
| 月間セッション | 7,350 | 10,000+ |
| RPM平均 | ¥170 | ¥250+ |
| Desktop fill rate | 64.89% | 75%+ |
| Bingクロールエラー率 | 56% | <30% |
| GSC低CTR記事 | 3件あり | 全件5%+CTR |

---

## 運用リズム

| 頻度 | タスク | コマンド |
|------|--------|---------|
| 週次 | 統合レポート生成 | `npm run weekly` |
| 週次 | 低CTR記事チェック | `npm run gsc -- --preset top-queries --limit 30` |
| 週次 | Bingクロールチェック | `npm run bing -- --preset crawl-stats` |
| 月次 | RPM深堀分析 | `npm run adsense -- --dimensions PLATFORM_TYPE_CODE` |
| 記事公開時 | IndexNow通知 | `npm run indexnow <url>` |
