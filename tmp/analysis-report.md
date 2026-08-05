# zidooka_writing 現状分析レポート

**作成日**: 2026-07-15
**プロジェクト**: zidooka.com WordPress ブログ

---

## 1. プロジェクト概要

| 項目 | 内容 |
|------|------|
| サイト | zidooka.com |
| プラットフォーム | WordPress |
| テーマ | zidooka-tw (Tailwind CSS v4) |
| 運用者 | 山口和紀 (単一 contributor) |
| 総コミット数 | ~330 commits |
| リポジトリ開始 | 2026年1月 ~ |
| 主な目的 | コンテンツ運用・テーマ開発・A/Bテスト・SEO改善 |

---

## 2. コンテンツ状況

### 公開記事
わずか **4件** のみ公開済み。

| ファイル | テーマ |
|----------|--------|
| ga4-notset-landing-page.md | GA4 (not set) ランディングページ解説 |
| ga4-notset-landing-page-en.md | 同上（英語） |
| ga4-notset-landing-page-jp.md | 同上（日本語） |
| opencode-session-storage-privacy.md | OpenCode セッションストレージ |

### 下書きストック
**約240件** の下書きファイル (`drafts/`) が未公開。

- テーマ: ChatGPT/Claude/Copilot エラー記事、GAS/Google API 記事、AIモデルレビュー、SEO/ツール記事、暦関連、技術解説 など
- 日英ペアで用意されているものが多数
- 直近で公開準備完了: `amazon-associate-evergreen-banners` (日英ペア)

### カテゴリ・タグ
- カテゴリ: **59カテゴリ**
- タグ: **500以上**
- カテゴリ設計はやや散逸（例: "Wordpress" と "WordPress" が併存、"WEB制作" と "WEB制作-用語" が分離）

---

## 3. テーマ開発 (zidooka-tw)

### PHP テンプレート一覧 (31ファイル)

| テンプレート | 状態 |
|-------------|------|
| single.php, archive.php, category.php, search.php, 404.php | 完備 |
| front-page.php | カスタムトップページ |
| functions.php (1136行) | 広告管理、CTA、A/Bテスト、SEO基盤 |
| 各種LPテンプレート | ビジネスLP、診断ツールLP、SVGコンバーター等 |
| カスタムテンプレート | ai-manga-gen1, gas_script 等 |

### 実装済み機能
- PostHog A/B テスト フレームワーク（`posthog-experiments.js`）
- 広告管理システム (`inc/ads.php` 相当)
- ダークモード対応
- CTA（Call to Action）表示制御
- SEO メタ最適化
- GAS（Google Apps Script）コード配布機能
- Tailwind CSS v4 + ビルドパイプライン

### 直近のテーマ改善 (直近10コミット)
1. Sponsored ラベル + スティッキーサイドバー広告導入
2. タグ認識バナー配信基盤 (FP Cafe デフォルト、Xserver は tech タグ)
3. FP Cafe A8 バナー (Buy Me a Coffee 置き換え)
4. Bing クロールエラー対策
5. SEO タイトル最適化 (GSCデータ活用)
6. zdk_header_image PostHog 実験 (A-9)
7. サーバーサイドコード折りたたみ実験
8. インライン CSS/JS 抽出によるパフォーマンス改善
9. Tailwind CDN → ローカルビルド移行
10. XSS エスケープ、PHP 8.x 安全性対応

---

## 4. インフラ・ツール

### 公開パイプライン
```
下書き (drafts/) → src/index.js (CLI) → WordPress (XML-RPC)
```
- バリデーション付き公開、日英ペア公開対応
- IndexNow 自動通知

### アナリティクス連携
| チャネル | ツール | コマンド |
|----------|--------|----------|
| Google Analytics 4 | Service Account | `npm run ga4` |
| Google Search Console | Service Account | `npm run gsc` |
| Google AdSense | OAuth Desktop | `npm run adsense` |
| Bing Webmaster | API Key | `npm run bing` |
| PostHog | Personal API Key | `npm run posthog:check` |
| 統合レポート | 全チャネル | `npm run weekly` |

### 自己改善ループ (`npm run improve`)
GA4/GSC/AdSense/Bing/PostHog データを自動収集 → TODO 自動生成。
判断記録 (`docs/decisions/`) の検証期限チェックも含む。

### テーマデプロイ
```
ローカル編集 (downloads/zidooka-tw/) → WebDAV push → 本番反映
```

### エージェント連携
- `daily-agent/YYYYMMDD.md` による日次連携ログ
- マルチエージェント競合防止 (特に PostHog 実験運用・テーマデプロイ時)
- OKF 形式ナレッジベース (`knowledge/`)

---

## 5. 主な課題

### 優先度: 高

1. **公開ボトルネック**
   - 公開記事 4件 に対し下書き ~240件
   - 多くの記事が書きかけまたは未公開のまま滞留
   - 機会損失: 検索需要に対してコンテンツが応えられていない

2. **カテゴリ設計の散逸**
   - 59カテゴリ、500+ タグだが重複・表記揺れあり
   - "Wordpress" vs "WordPress"、"WEB制作" vs "WEB制作-用語" 等
   - ナビゲーション・SEO 観点で整理が必要

### 優先度: 中

3. **単一運用者への依存**
   - 全コミットが 1人
   - 並行作業の制限、ナレッジシェアの課題
   - AGENTS.md でマルチエージェント運用を試みているが実効性は限定的

4. **テーマ本番反映コスト**
   - WebDAV 経由の手動デプロイ
   - ローカル → リモートの差分管理が煩雑
   - デプロイ検証フローが未整備

### 優先度: 低

5. **アナリティクス分析サイクルが未成熟**
   - GA4/GSC データ連携は確立したが、分析→記事改善のループがまだ十分に回っていない
   - 週次レポートは自動生成されるが、そこからの具体的アクションに繋がっていない

6. **テスト不在**
   - ユニットテスト・結合テストが存在しない
   - CLI やテーマ変更のリグレッションリスクが高い

---

## 6. 強み

1. **充実したツール基盤**: 公開・分析・デプロイまで CLI 一元管理
2. **豊富な下書きストック**: 適切に公開すれば強力なコンテンツ資産に
3. **モダンなテーマ**: Tailwind v4, ダークモード, パフォーマンス最適化済み
4. **A/B テスト実装済み**: PostHog によるデータドリブン改善が可能
5. **自己改善システム**: データ収集→TODO 生成の自動化ループ稼働中
6. **判断記録文化**: 意思決定と検証を構造化して追跡

---

## 7. 推奨アクション

### 短期 (今週)
1. `amazon-associate-evergreen-banners` 日英ペアを公開
2. PostHog A/B 実験 (zdk_header_image: A-9) の結果確認と closeout
3. 週次レポート実行 (`npm run weekly`)

### 中期 (2-4週間)
4. カテゴリの統廃合 (重複カテゴリのマージ)
5. 下書きストックから公開優先順位をつけて週2-3件ペースで公開
6. GSC 低CTR記事のタイトル最適化を定期実行

### 長期 (1-3ヶ月)
7. CI/CD パイプライン整備 (GitHub Actions での自動テスト・デプロイ)
8. 公開記事数を 50+ に増やし、オーガニックトラフィック基盤を構築
9. カレンダーサブページの実装 (docs/CALENDAR_* 計画に基づく)
10. 自己改善ループの週次自動実行定着
