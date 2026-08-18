#!/usr/bin/env node
/**
 * A8.net 広告リンクの死活チェック
 *
 * inc/ads.php の a8_offers テーブルからバナー画像URL・クリックURL・計測ピクセルを抽出し、
 * HTTPステータスと Content-Type を確認する。プログラム終了などでリンクが死ぬと
 * バナーが壊れて収益ゼロのまま気づけないため、月次で実行する（docs/ADS_MANAGEMENT.md）。
 *
 * 使い方: npm run ads:check
 */

import { readFileSync } from 'fs';
import { fileURLToPath } from 'url';
import path from 'path';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ADS_PHP = path.join(__dirname, '..', 'downloads', 'zidooka-tw', 'inc', 'ads.php');

const src = readFileSync(ADS_PHP, 'utf8');

// a8_offers ブロック内の 'key' => 'https://...' を拾う
const urlEntries = [];
const re = /'(click|img|pixel)'\s*=>\s*'(https:\/\/[^']+)'/g;
let m;
while ((m = re.exec(src)) !== null) {
  urlEntries.push({ kind: m[1], url: m[2] });
}

if (urlEntries.length === 0) {
  console.error('inc/ads.php からA8のURLを抽出できませんでした。フォーマットが変わった可能性があります。');
  process.exit(1);
}

const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) zidooka-ads-check/1.0';

async function probe(url) {
  try {
    const res = await fetch(url, {
      method: 'GET',
      redirect: 'manual', // px.a8.net は 302 が正常。追跡するとクリック計測が汚れるので追わない
      headers: { 'user-agent': UA },
      signal: AbortSignal.timeout(15000),
    });
    const type = res.headers.get('content-type') || '';
    let bytes = 0;
    if (res.status >= 200 && res.status < 300) {
      const buf = await res.arrayBuffer();
      bytes = buf.byteLength;
    }
    return { status: res.status, type, bytes };
  } catch (e) {
    return { status: 0, type: '', bytes: 0, error: e.message };
  }
}

function judge(kind, r) {
  if (r.status === 0) return 'NG (接続失敗)';
  if (kind === 'click') {
    // クリックURLはリダイレクトが正常。200で素通しはA8側の異常表示ページの可能性
    return r.status >= 300 && r.status < 400 ? 'OK' : `要確認 (status ${r.status})`;
  }
  if (r.status !== 200) return `NG (status ${r.status})`;
  if (!r.type.startsWith('image/')) return `要確認 (content-type: ${r.type})`;
  // バナー画像が1KB未満なら差し替え用のダミー画像の可能性が高い
  if (kind === 'img' && r.bytes < 1024) return `要確認 (画像が ${r.bytes} bytes と小さい)`;
  return 'OK';
}

console.log(`A8リンク死活チェック (${urlEntries.length} URLs) — ${new Date().toISOString().slice(0, 10)}\n`);

let ngCount = 0;
for (const { kind, url } of urlEntries) {
  const r = await probe(url);
  const verdict = judge(kind, r);
  if (!verdict.startsWith('OK')) ngCount++;
  const short = url.length > 78 ? url.slice(0, 75) + '...' : url;
  console.log(`[${verdict}] ${kind.padEnd(5)} ${r.status} ${short}`);
  if (r.error) console.log(`         └ ${r.error}`);
}

console.log(`\n結果: ${urlEntries.length - ngCount} OK / ${ngCount} 要対応`);
if (ngCount > 0) {
  console.log('要対応のリンクはA8管理画面でプログラム状態を確認し、inc/ads.php の a8_offers を差し替えるか status を paused にすること。');
  process.exit(1);
}
