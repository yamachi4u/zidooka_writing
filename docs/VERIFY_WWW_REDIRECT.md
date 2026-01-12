www リダイレクト適用チェック手順

更新日: 2026-01-12

■ 目的
- `https://zidooka.com` アクセスを `https://www.zidooka.com` へ 301 で統一できているか検証

■ 手順（CLI）
1) `curl -I https://zidooka.com` を実行
   - 期待: `HTTP/1.1 301 Moved Permanently`
   - `Location: https://www.zidooka.com/` が返ること
2) `curl -I https://www.zidooka.com` を実行
   - 期待: `HTTP/2 200`（ホスティングにより `HTTP/1.1 200` の場合あり）
3) ループ確認
   - `curl -I -L https://zidooka.com` で最終到達が `https://www.zidooka.com/` かつ 200 であること

■ 手順（ブラウザ）
- シークレットウィンドウで `https://zidooka.com` にアクセスし、アドレスバーが `https://www.zidooka.com` へ変化することを確認

■ 注意
- Cloudflare 等CDN使用時は、DNS/リダイレクトルールで統一する方法もある（301推奨）
- 既存の他Rewrite規則よりも前にwww統一ルールを置くこと