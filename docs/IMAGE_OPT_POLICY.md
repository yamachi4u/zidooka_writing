画像最適化ポリシー（下書き）

更新日: 2026-01-12

■ フォーマット
- 既定は WebP、対応ブラウザで AVIF も許容（CDN/プラグインで自動変換）
- 透過/ロゴ等はSVG優先（ビットマップ透過はPNG）

■ 解像度・リサイズ
- LCP候補（ヒーロー/アイキャッチ）は画面幅に応じて 768/1200/1600/1920 を用意
- 一覧やサムネは 320/480/640/960 など最小限で十分

■ 配信（srcset/sizes）
- すべての挿入画像に `srcset` と適切な `sizes` を付与
- LCP候補は事前読み込み `<link rel="preload" as="image" imagesrcset="..." imagesizes="...">` を検討

■ ローディング
- 折返し下は `loading="lazy"`、LCP候補は eager（`fetchpriority="high"` の活用）
- CLS防止のため `width`/`height` を明示

■ 圧縮
- 画質初期値: WebP 75–85 / AVIF 45–60 を目安に実機で確認
- メタ情報を削除（著者/撮影位置など不要なEXIF）

■ 運用ガイド
- WordPress: 生成サイズの棚卸し（不要サイズは停止）、自動変換/置換を有効化
- OGP/Twitter用は 1200×630（≤200KB目標）を別途用意
- ファイル名は英数スネークケース、内容を表す語を使用

■ CDN
- 画像CDN（Cloudflare Images/Universal/Buckets等）で自動変換と最適化
- キャッシュは 1年＋`immutable`、クエリ版管理でキャッシュ更新