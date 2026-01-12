CSS/JS 最適化 計画（下書き）

更新日: 2026-01-12

■ 目標
- LCPとTTIの短縮、CLS/INPを悪化させない形での削減。

■ CSS
- [ ] クリティカルCSS抽出（Above-the-fold）→ `<style>` でインライン化
- [ ] 残余CSSは遅延適用：`<link rel="preload" as="style" onload="this.rel='stylesheet'">`
- [ ] 未使用CSSの削除（テーマ/プラグインの棚卸し、HCB等の読み込み条件最適化）
- [ ] `bundle.css` の分割（ページタイプ/機能ごと）とサイズ目標 100KB 未満
- [ ] 静的配信の強キャッシュ（1年＋`immutable`）

■ JS
- [ ] jQuery依存の判定：不要なら撤廃、必要時も `jquery-migrate` は原則停止
- [ ] 自前スクリプトは `defer`、サードパーティは `async`
- [ ] 実行タイミングを遅延（不要なDOMContentLoaded/ready依存を解消）
- [ ] 機能単位で読み分け（コード分割・条件読み込み）
- [ ] サードパーティの整理（ads/analyticsの最小限化）

■ 配信/サーバ
- [ ] Brotli/gzip圧縮、HTTP/2/3の確認
- [ ] Early Hints/Server Pushは使わずPreloadを明示
- [ ] CDNでのキャッシュ最適化（画像/CSS/JSの圧縮・変換）

■ 検証
- [ ] PageSpeed/Lighthouse（モバイル）でリグレッションを逐次確認
- [ ] CrUXとSearch Consoleのウェブに関する主な指標をモニタ