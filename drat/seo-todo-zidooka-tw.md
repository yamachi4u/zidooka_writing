# マスターTODO

> 更新: 2026-05-13

---

## ✅ 完了

### zidooka-tw テーマ
- [x] preconnect/dns-prefetch全ページ化
- [x] Organization schema + sameAs
- [x] noindex 薄いアーカイブ
- [x] LCP最適化（front + single）
- [x] Breadcrumb JSON-LD 全ページ対応
- [x] アーカイブページネーション rel prev/next
- [x] TOCクリック GA4イベント（zdk_toc_click）
- [x] 404 GA4イベント（zdk_404）
- [x] デッドコード削除（banner 300行）
- [x] AdSense重複解消
- [x] CSSアニメーション（fade-in, scroll spy, いいねpop, toast, カテゴリツリー, 選択範囲, フォーカスリング, スクロールバー, ヘッダーシャドウ）
- [x] 404ページ改善（最近の投稿＋カテゴリ）
- [x] 検索自動フォーカス

### GA4/GSC 分析基盤
- [x] .env に GA4/GSC設定追加
- [x] `npm run seo:weekly` / `seo:monthly` / `seo:errors`
- [x] GSCギャップ分析レポート作成
- [x] エラー記事フォローアップ監視（19ページ）

### 記事（GSCギャップ補填）
- [x] 「ポストを読み込めません 特定の人」→ 5/14予約
- [x] 「Google hasn't verified this app」→ 5/15予約
- [x] 「Codex CLI 重い・レート制限」→ 5/16予約
- [x] 「Copilot premium request allowance」→ 5/17予約
- [x] 「Something went wrong. Try reloading.」→ 5/18予約
- [x] 「STATUS_ACCESS_VIOLATION」→ 5/19予約
- [x] 「ポストを読み込めません アカウント削除」（既存記事タイトル改善）→ 5/20予約

### カレンダー（benri-tools）
- [x] 広告フォールバックチェーン（FallbackAdSlot）
- [x] GA4イベント追加（detail_open, share, filter, purpose）
- [x] URL State Sync 確認＆強化

### 運用フロー文書化
- [x] AGENTS.md に SEO Operations セクション追加
- [x] `drat/ga4-operations-plan.md` 作成

---

## TODO

### コード変更
#### 優先度中
- [ ] **記事公開イベントをMeasurement Protocolで送信** → `zdk_post_publish`

### 運用プロセス
- [ ] **週次**: `npm run seo:errors` → 低CTR記事のタイトル修正
- [ ] **隔週**: 内部検索クエリレビュー / GSCギャップ分析
- [ ] **月次**: 記事パフォーマンスレビュー（`npm run seo:monthly`）
- [ ] **四半期**: コンテンツギャップ分析 / テクニカルSEO監査
- [ ] **1週間後**: カレンダーGA4イベントのデータ確認

---

## ナレッジ

- テーマ変更 → `npm run remote:agent -- push`
- 最新pull → `npm run remote:agent -- pull --file="zidooka/wp-content/themes/zidooka-tw/xxx.php" --out="tmp_remote_agent/xxx.latest.php"`
- GA4分析 → `npm run seo:errors` / `npm run seo:weekly`
- 記事予約 → `node src/index.js schedule drafts/file.md`
