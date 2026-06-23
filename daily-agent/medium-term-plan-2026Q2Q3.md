# 中期計画 2026-Q2/Q3 — ZIDOOKA エコシステム

> 作成: 2026-06-05 Opencode  
> 対象: zidooka.com + tools.zidooka.com + KDP  
> 目標: 月間収益 10万円（現在 約2.5万円の推定ラインから）  
> このファイルは全エージェント（Opencode / Codex / Claude Code）の共有参照点です。

---

## 資産マップ

```
zidooka.com (WordPress)          tools.zidooka.com (Next.js)       KDP (Kindle)
├─ 855記事（日英バイリンガル）     ├─ ~55ツール（全クライアントサイド） ├─ 30冊
├─ AdSense + Auto Ads            ├─ AdSense（AdSlot.tsx）          ├─ KENP収益が主
├─ コンサルCTA（受注モード切替可）├─ PostHog A/Bテスト              ├─ 3冊「未出版の変更あり」
├─ GA4 / GSC / Bing / AdSense API├─ GA4 / GSC / Bing / AdSense API ├─ 言語700シリーズ展開中
├─ PostHog（A/B + error tracking）├─ カレンダーSEO（Bing強）         └─ ドイツ語/イタリア語700未着手
└─ Bing流入 57.7%                └─ IndexNow自動通知
```

---

## Phase 1: 基盤修復（6月中）← 今ここ

### 1-1. Bingクロールエラー解消 [緊急]
- **担当**: 全エージェントが認識すべき
- **状況**: Bingがトラフィックの57.7%。クロールエラー率51-93%
- **アクション**: `npm run bing -- --preset crawl-stats` → エラーURL特定 → サーバー会社確認
- **優先度**: 最優先。放置でトラフィック半減リスク

### 1-2. KDP未出版クリア
- **担当**: 誰でも
- **アクション**: KDPコンソール →「未出版の変更あり」3冊 → 内容確認 → 出版
- ASIN: B0GPLT7RB4（スペイン語700 Part 2）, B0DVZRZL32（TOEIC語源）, B0C72KSXL1（起業術）
- スペイン語700 Part 2 の価格を ¥780 → ¥500 に統一

### 1-3. 週次レポート習慣化
- **担当**: 週次でエージェントが自律実行
- **アクション**: 毎週月曜に `npm run weekly`（zidooka + benri-tools 両方）
- 出力を `daily/weekly/` に保存。異常値があれば即日対応

---

## Phase 2: トラフィック拡大（7-8月）

### 2-1. サイト内検索ギャップ記事の作成（zidooka）
- **トリガー**: GA4の `zdk_search` イベント（本日実装済み）
- **アクション**: 2週間データを溜める → 検索0件ヒットのクエリをリストアップ → 記事化
- **期待効果**: 内部回遊率 +20%、PV +10-15%

### 2-2. 高CTR × 低PV記事のタイトル改善（zidooka）
- **データソース**: `npm run gsc -- --preset top-queries --limit 100`
- **アクション**: CTR > 5% かつ PV < 100/月 の記事タイトルを検索クエリに最適化
- **期待効果**: PV +5-10%

### 2-3. エラーメッセージSEOの継続強化（zidooka）
- 既にGSCギャップ分析→記事化のパイプラインあり
- 新しいエラーメッセージクエリを毎月5件ターゲット
- タイトルルール: エラー文の最初の5語を含める

### 2-4. benri-tools ツール別SEOランディングページ
- 各ツールに個別のメタデータは既にある（`lib/tool-metadata.ts`）
- GSCで impressions > 100, CTR < 3% のツールページを特定 → description改善
- `/jp/calendar/*` 系の下層ページ（定義・用途別）のmeta titleチューニング

### 2-5. カレンダーオンボーディング実験の勝敗判定（benri-tools）
- 実験: `onboarding-experiment-ph`（control vs immediate-short）
- データが十分溜まったら `npm run ph:experiment` で判定
- 勝者をcontrol 100%に、負けコード削除

---

## Phase 3: 収益改善（8-9月）

### 3-1. AdSense RPM回復（両サイト）
- **データ**: 4月末¥458→5月下旬¥100
- **アクション**: デバイス別・国別RPMを分析。モバイルRPMが低いならAMP検討
- benri-tools: `AdSlot` の配置場所A/Bテスト（PostHog feature flag）

### 3-2. benri-tools 広告枠最適化
- カレンダー詳細ドロワー内の広告→フォールバックリンクのCTR測定
- ツールページへの広告配置（現在はツールページにAdSlotなしの可能性）

### 3-3. KDPシリーズ拡大
- ドイツ語700 Part 1 + Part 2 をQ3中に出版
- イタリア語700、フランス語700 Part 3 も候補
- 表紙は ImageGen→デザイン確定→SVGテンプレート量産のパイプライン

### 3-4. コンサル受注モードの運用
- 現在は「広告モード」で稼働中（本日設定）
- 受注が必要になったらCTA Settings で「受注モード」に切替
- 切替の判断基準: 月間問い合わせ数が0の状態が2ヶ月続いたら

---

## Phase 4: 仕組み化（9月以降）

### 4-1. 自律改善ループの完全自動化
- `npm run improve`（benri-tools）→ データ収集→分析→TODO生成→PR自動作成
- zidooka側も同様のスクリプトを整備

### 4-2. クロスプロジェクト分析
- 両サイトのGA4データを統合分析
- どのツールのユーザーがzidookaの記事も読むか？
- zidooka記事→benri-tools の流入経路分析

### 4-3. AI検索最適化
- `llms.txt` / `llms-full.txt` は既にbenri-toolsにある
- zidookaにも同等の仕組みを
- ChatGPT/Perplexity経由の流入を増やす

---

## エージェント間共有ルール

1. **このファイルを最初に読む**: どのエージェントもセッション開始時にこの計画を確認
2. **daily-agentに作業を記録**: 各プロジェクトの `daily-agent/YYYYMMDD.md` に実施内容を追記
3. **Phase/タスクの進捗を更新**: タスクが完了したらこのファイルの `[ ]` → `[x]` に
4. **判断に迷ったら**: 各プロジェクトの `AGENTS.md` → この中期計画 → ユーザーに確認
5. **並行作業の調整**: 同じPhaseのタスクは異なるエージェントで並行可。daily-agentで競合チェック

---

## 次にやるべきこと（優先度順）

1. `npm run bing -- --preset crawl-stats` を benri-tools で実行 → エラーURL確認
2. KDPコンソールで「未出版の変更あり」3冊の状況確認
3. `daily-agent/` に本日のエントリを追記（全エージェント）
4. 週次レポートの結果確認（`npm run weekly`）
