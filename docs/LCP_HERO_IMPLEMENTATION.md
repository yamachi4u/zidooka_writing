ホームLCP（ヒーロー画像）実装計画（具体案）

更新日: 2026-01-12

■ 目的
- Largest Contentful Paint の短縮：ヒーロー画像の読み込みを最優先にし、描画をブロックしない。

■ 実装ポイント（WordPress 子テーマ想定）
- LCP対象画像を eager かつ高優先度で取得
  - `<img loading="eager" fetchpriority="high">` を適用
  - 適切な `width`/`height`、`srcset`/`sizes` 指定
- 事前読み込み（preload）
  - `<link rel="preload" as="image" href="..." imagesrcset="..." imagesizes="...">`
  - 代替として `wp_resource_hints` / `wp_enqueue_scripts` で出力
- CSSブロッキング抑制
  - クリティカルCSSを `<style>` でインライン化、残余は遅延

■ テンプレート修正（例: `front-page.php` or `header.php`）
```php
<?php
// 例: ヒーロー画像IDを取得（AIOSEO/カスタムフィールド/テーマ設定等に合わせて）
$hero_id = get_post_thumbnail_id(get_option('page_on_front'));
if ($hero_id) {
  // 幅ごとにsrcsetをWordPressが自動生成
  echo wp_get_attachment_image(
    $hero_id,
    'full',
    false,
    [
      'class' => 'hero-image',
      'loading' => 'eager',
      'fetchpriority' => 'high',
      'decoding' => 'async',
      'sizes' => '(min-width: 1024px) 1200px, 100vw',
      // width/heightはメディアに登録された実サイズから自動埋め込み（必要なら明示）
    ]
  );
}
?>
```

■ preload の付与（`functions.php`）
```php
<?php
// ヒーロー画像をpreload（imagesrcset/imagesizes対応）
add_action('wp_head', function(){
  if (!is_front_page()) return;
  $hero_id = get_post_thumbnail_id(get_option('page_on_front'));
  if (!$hero_id) return;
  $src_full = wp_get_attachment_image_url($hero_id, 'full');
  $srcset   = wp_get_attachment_image_srcset($hero_id, 'full');
  $sizes    = '(min-width: 1024px) 1200px, 100vw';
  if ($src_full && $srcset) {
    printf(
      "<link rel=\"preload\" as=\"image\" href=\"%s\" imagesrcset=\"%s\" imagesizes=\"%s\">\n",
      esc_url($src_full), esc_attr($srcset), esc_attr($sizes)
    );
  }
});
```

■ 代替: `wp_get_attachment_image_attributes` で一括付与
```php
<?php
add_filter('wp_get_attachment_image_attributes', function($attr, $attachment, $size){
  if (is_front_page()) {
    if (!isset($attr['class'])) $attr['class'] = '';
    if (strpos($attr['class'], 'hero-image') !== false) {
      $attr['loading'] = 'eager';
      $attr['fetchpriority'] = 'high';
      $attr['decoding'] = 'async';
      // $attr['sizes'] = '(min-width: 1024px) 1200px, 100vw'; // 必要なら
    }
  }
  return $attr;
}, 10, 3);
```

■ 検証チェックリスト
- [ ] DevTools/LighthouseでLCP要素がヒーロー画像であることを確認
- [ ] `loading=eager`/`fetchpriority=high` が出力されている
- [ ] `<link rel=preload as=image>` がheadに存在し、ネットワークで先行取得
- [ ] 画像の `width`/`height` が明示されCLSが抑制されている
- [ ] 画像フォーマット（WebP/AVIF）と `srcset/sizes` が適切

■ ロールアウト手順
1) 子テーマでテンプレート/関数を追加しステージングで検証
2) LighthouseモバイルでLCP改善を比較（Before/After）
3) 本番適用後、CrUX/SCの指標をモニタ（2–4週）

