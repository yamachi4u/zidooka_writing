実サイト反映手順（WordPress + Apache）

更新日: 2026-01-12

■ 前提
- 運用対象: `https://www.zidooka.com`
- サーバ: Apache（.htaccess利用可）、WordPress 運用中（AIOSEO等）

■ 手順 1: www 統一リダイレクト
1) サイトルートの `.htaccess` に以下を最上段へ追加（既存Rewriteと重複注意）
   - `docs/snippets/htaccess/redirect_www.htaccess` の内容を貼付
2) `https://zidooka.com` → `https://www.zidooka.com` へ 301 で統一されることを確認

■ 手順 2: セキュリティ/キャッシュ/圧縮
1) `.htaccess` に以下を追記
   - `docs/snippets/htaccess/security_headers.htaccess`
   - `docs/snippets/htaccess/cache_compress.htaccess`
2) HSTS/CSP-Report-Only/Cache-Control/圧縮の有効化を`curl -I`等で確認

■ 手順 3: ホームのSEO設定（AIOSEO）
1) タイトル/ディスクリプション/H1を設定（文案は `docs/SEO_HOME_DRAFT.md`）
2) OGPの site_name 体裁を「ZIDOOKA!」に統一、画像は軽量版（1200×630）

■ 手順 4: アナリティクスの一本化
1) 実装方式を ①GTM ②gtag ③MonsterInsights のいずれかに統一
2) 未設定プラグインの残骸を無効化/削除（重複発火防止）
3) チェックリストは `docs/ANALYTICS_CHECKLIST.md`

■ 手順 5: 初期パフォーマンス改善
1) 画像の `loading`/`width`/`height` の是正（上折り以外は lazy）
2) jQuery Migrate の停止（依存が無ければ）
3) 大CSSの段階的分割/遅延導入（詳細は `docs/PERF_CSS_JS_PLAN.md`）

■ 手順 6: 計測/監視
1) Search Console/CrUX/Lighthouse（モバイル）で改善を計測
2) エラー時はCSPを Report-Only のまま許可ドメインを精査（次タスク参照）

■ ロールバック指針
- .htaccess 変更は必ずバックアップし、問題時は直前版へ復旧
- テーマ編集は子テーマで行い、差分をGit管理