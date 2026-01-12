LCP（Largest Contentful Paint）候補の抽出メモ

更新日: 2026-01-12

■ 現状の仮説（ホーム）
- ヒーロー/アイキャッチ画像（例: `Slide-16_9-1.png` 系）がLCP候補
- 一部画像に `loading="lazy"` が付いているため、LCPに不利（eager化推奨）

■ 改善方針
- LCP対象は `loading="eager"`、`fetchpriority="high"`、適切な `srcset/sizes`
- 解像度の見直し（ビューポートに最適化）とWebP/AVIFの配信
- CSSブロッキング低減（クリティカルCSS化）で描画を先行

■ 設定チェックリスト
- [ ] LCP候補画像のDOM位置（折り返し上にあるか）
- [ ] `width`/`height` 指定と縦横比の維持
- [ ] `preload`（必要に応じて `imagesrcset`/`imagesizes`）
- [ ] Core Web Vitalsのフィールドデータ（CrUX/SC）で監視

■ 計測方法メモ
- Lighthouse（モバイル/スロットリング）でLCP要素を特定
- Chrome DevTools Performanceタブ（Web Vitals表示）でフレームごとに確認