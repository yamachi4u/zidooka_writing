CSP レポート設定（Report-Only からの導入手順）

更新日: 2026-01-12

■ 方針
- まずは Report-Only で導入し、実運用で違反ログを収集→許可リストを整備→最終的にEnforceへ移行。

■ 手順
1) `.htaccess` に Report-Only ヘッダを追加（雛形は `docs/snippets/htaccess/security_headers.htaccess`）
   - `Content-Security-Policy-Report-Only: default-src 'self'; ...; report-uri /csp-report`
2) レポート受け口を用意
   - 例: 簡易PHPエンドポイント `/csp-report`（POST JSONをログファイルへ）
3) 数日～1週間、実トラフィックの違反レポートを収集
4) 許可先（`script-src`, `img-src`, `connect-src`, `frame-src` 等）を精査
5) `Content-Security-Policy`（Enforce）に切替、`Report-To`/`report-uri` は継続

■ 例: PHP 受信エンドポイント（簡易）
```php
<?php
// /csp-report/index.php
header('Content-Type: application/json');
$raw = file_get_contents('php://input');
if ($raw) {
  $dir = __DIR__ . '/logs';
  if (!is_dir($dir)) { mkdir($dir, 0755, true); }
  $file = $dir . '/csp_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.json';
  file_put_contents($file, $raw . "\n", FILE_APPEND);
}
echo json_encode(['ok' => true]);
```

■ 初期許可の目安（要実測調整）
- `script-src`: `'self' 'unsafe-inline'` + `www.googletagmanager.com`, `pagead2.googlesyndication.com`
- `img-src`: `'self' data: https:`（広告やOGP含む）
- `style-src`: `'self' 'unsafe-inline' https:`
- `font-src`: `'self' data: https:`
- `connect-src`: `'self' https:`（GA4/GTMの送信先を含める）
- `frame-src`: `youtube.com`, `*.doubleclick.net`, `tpc.googlesyndication.com` など必要最小限

■ 注意
- Report-Only でもブロックはされないが、違反が多い箇所はEnforce移行前に必ず解消
- `unsafe-inline`/`unsafe-eval` は段階的に削減し、`nonce`/`hash` へ移行するのが理想