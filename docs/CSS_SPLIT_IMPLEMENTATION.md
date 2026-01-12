子テーマCSS分割 実装計画（picostrap5-child-base想定）

更新日: 2026-01-12

■ 目的
- `bundle.css` 依存を段階的に分割し、Above-the-foldの描画を先行。未使用CSSを削減。

■ 戦略
1) クリティカルCSS抽出 → インライン
2) ページタイプ別CSS（home, single, archive, common）に分割
3) 残余CSSは遅延読み込み（preload + onloadでrel切替）

■ 実装フロー
1) 既存 `bundle.css` のマッピング
   - どのセレクタがどのページで使用されるかを棚卸し
2) 子テーマの `functions.php` で読み込み制御
   - 例: `home.css`, `single.css`, `archive.css`, `common.css`
3) クリティカルCSSは `wp_head` でインライン

■ コード例（`functions.php`）
```php
<?php
add_action('wp_enqueue_scripts', function(){
  // 既存bundleの読み込みを停止（テーマ実装に応じてハンドル名を確認）
  wp_dequeue_style('bundle');
  wp_deregister_style('bundle');

  // 共通CSSは通常linkで
  wp_enqueue_style('zdk-common', get_stylesheet_directory_uri() . '/css/common.css', [], '1.0');

  if (is_front_page()) {
    // ホーム専用CSSをpreloadで先行→onloadでstylesheet化
    $href = get_stylesheet_directory_uri() . '/css/home.css';
    printf('<link rel="preload" as="style" href="%s" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n", esc_url($href));
    // noscript fallback
    printf('<noscript><link rel="stylesheet" href="%s"></noscript>' . "\n", esc_url($href));
  }
  elseif (is_single()) {
    wp_enqueue_style('zdk-single', get_stylesheet_directory_uri() . '/css/single.css', [], '1.0');
  }
  elseif (is_archive()) {
    wp_enqueue_style('zdk-archive', get_stylesheet_directory_uri() . '/css/archive.css', [], '1.0');
  }
}, 20);

// クリティカルCSSをインライン（ホーム）
add_action('wp_head', function(){
  if (!is_front_page()) return;
  $path = get_stylesheet_directory() . '/css/critical-home.css';
  if (file_exists($path)) {
    $css = file_get_contents($path);
    echo "<style id=\"zdk-critical-home\">{$css}</style>\n";
  }
}, 5);
```

■ ファイル構成（例）
- `css/critical-home.css`（上折りに必要な最小限）
- `css/common.css`（ヘッダ/フッタ/共通部品）
- `css/home.css`
- `css/single.css`
- `css/archive.css`

■ 検証
- FOUCの有無、レイアウト崩れの確認
- LighthouseでCSSブロッキングと未使用CSSの割合を比較

■ ロールアウト
- まずホームのみで適用 → 問題なければ各テンプレートへ水平展開