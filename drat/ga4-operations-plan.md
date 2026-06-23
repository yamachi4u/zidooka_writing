# GA4 イベント設計 & 運用フロー

---

## 追加したいカスタムイベント

### 1. TOC クリック追跡
- **場所**: `single.php` の TOC リンククリックハンドラ
- **イベント名**: `zdk_toc_click`
- **パラメータ**: `heading_text`, `heading_tag` (h2/h3)
- **既存コードへの追加**: `a.addEventListener('click', ...)` 内で `gtag('event', 'zdk_toc_click', { heading_text: ..., heading_tag: ... })`

### 2. 外部リンククリック追跡
- **場所**: `functions.php`（フッターの全リンク / サイドバー / CTA）
- **方法**: 既存コードには手を入れずに、GA4 の Enhanced Measurement で「アウトバウンドクリック」を有効にする（管理画面でONにするだけ）
- **代替**: どうしてもカスタムが必要なら `functions.php` に以下のスニペット追加:
  ```php
  add_action('wp_footer', function(){
    if (wp_script_is('gtag', 'enqueued')) return;
    ?>
    <script>
    document.addEventListener('click', function(e) {
      var link = e.target.closest('a[href]');
      if (!link) return;
      var href = link.getAttribute('href');
      if (href && href.startsWith('http') && !href.includes(location.hostname)) {
        gtag('event', 'click', {
          event_category: 'outbound',
          event_label: href,
          transport_type: 'beacon'
        });
      }
    });
    </script>
    <?php
  });
  ```

### 3. 404 ページ到達追跡
- **場所**: `404.php`
- **イベント名**: `zdk_404`
- **パラメータ**: `referrer`, `requested_url`
- **コード**:
  ```php
  <?php if (function_exists('gtag')) : ?>
  <script>
  gtag('event', 'zdk_404', {
    referrer: document.referrer || '(direct)',
    requested_url: location.pathname + location.search
  });
  </script>
  <?php endif; ?>
  ```

### 4. 内部検索クエリ追跡（強化）
- **現状**: GA4 Enhanced Measurement で view_search_results は自動取得される
- **対応**: 検索結果が0件だった場合のイベントを追加（検索語句は自動取得されるので不要）

### 5. 記事公開イベント
- **現状**: `transition_post_status` フックで X投稿している（`functions.php:1119`）
- **追加**: 同じフックの中で GA4 にも送信
  ```php
  // 上と同じフック内に追加
  $ga4_id = defined('GA_MEASUREMENT_ID') ? constant('GA_MEASUREMENT_ID') : 'G-VNF3D5QY6E';
  $payload = [
    'client_id' => uniqid('publish_', true),
    'events' => [[
      'name' => 'zdk_post_publish',
      'params' => [
        'post_id' => $post->ID,
        'post_title' => mb_substr($title, 0, 100),
        'post_url' => $url,
        'post_category' => implode(',', wp_get_post_categories($post->ID, ['fields' => 'names'])),
      ]
    ]]
  ];
  // Measurement Protocol で送信（サーバーサイド）
  ```

---

## 定期運用サイクル

### 週次（GitHub Actions 自動実行）
- **GA4 レポート**: `npm run ga4` → 前週のトップページ・セッション・エンゲージメント確認
- **GSC クエリ確認**: `npm run gsc` → 掲載順位変動・CTR低下をチェック
- **チェックポイント**: セッション数が前週比 -20% 以上の記事がないか

### 隔週（手動）
- **内部検索クエリレビュー**: GA4 > レポート > エンゲージメント > イベント > `view_search_results`
  - 検索されているのに記事がないキーワード → 執筆候補
  - 記事があるのに検索されている → タイトル/メタディスクリプションが適切でない可能性
- **404 エラーチェック**: GSC > ページ > 404
  - 内部リンクの修正 or リダイレクト設定

### 月次（手動）
- **記事パフォーマンスレビュー**:
  ```powershell
  cd C:\Users\user\Documents\zidooka_writing
  node scripts/ga4-report.mjs --period 30d
  node scripts/gsc-query.mjs --period 30d
  ```
- **対象**: 公開から30日経過した記事
- **アクション**:
  - 掲載順位 20位以下、CTR 1%未満 → タイトル/メタディスクリプション改善
  - 離脱率 80%以上、平均エンゲージメント時間 30秒未満 → 記事内容の見直し（導入部を改善）
  - 直帰率が低い（＝読まれている）がCVRが低い → CTAの改善 or 内部リンク追加

### 四半期（戦略）
- **コンテンツギャップ分析**:
  - GSC のクエリ一覧から「インプレッションはあるが自サイトにクリックがない」クエリを抽出
  - 競合サイトに取られているキーワードを特定
  - 新規記事 or 既存記事のリライトを計画
- **テクニカルSEO監査**:
  - PageSpeed Insights で Core Web Vitals 確認（モバイル/デスクトップ）
  - 構造化データのバリデーションチェック（Rich Results Test）
  - 被リンクプロファイルの簡易チェック

---

## 運用効率化の仕組み

### CLI エイリアス（npm scripts）
現在の `ga4` / `gsc` に加えて、以下があると便利:

```json
{
  "seo:weekly": "node scripts/ga4-report.mjs --period 7d && node scripts/gsc-query.mjs --period 7d",
  "seo:monthly": "node scripts/ga4-report.mjs --period 30d && node scripts/gsc-query.mjs --period 30d"
}
```

### 気づきを記録する場所
- **即時メモ**: `drat/YYYYMMDD-seo-note.md`
- **四半期レビュー**: `docs/seo/review-YYYY-QQ.md`
- **変更履歴**: `drat/seo-todo-zidooka-tw.md` に進捗を追記

---

## 優先順位（今すぐやること）

- **[高]** GA4 Enhanced Measurement で「アウトバウンドクリック」「ファイルダウンロード」「スクロール深度」を有効化
  - → 管理画面 > 管理 > データストリーム > 拡張計測機能 > すべてON（コード変更不要）
- **[中]** 404 ページに `zdk_404` イベントを追加
- **[低]** 記事公開イベントを Measurement Protocol で送信
- **[中]** `npm run seo:weekly` を package.json に追加
