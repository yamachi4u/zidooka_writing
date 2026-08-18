#!/usr/bin/env node
import 'dotenv/config';

const key = process.env.INDEXNOW_KEY || '8ba925c14f67de03';
const siteUrl = process.env.INDEXNOW_SITE_URL || 'https://www.zidooka.com';

const urls = process.argv.slice(2).filter(a => !a.startsWith('-'));
if (urls.length === 0) {
  console.log(`Usage: node scripts/ping-indexnow.mjs <url> [url...]
Pings IndexNow to notify search engines of URL changes.

Environment:
  INDEXNOW_KEY       (default: 8ba925c14f67de03)
  INDEXNOW_SITE_URL  (default: https://www.zidooka.com)

Examples:
  node scripts/ping-indexnow.mjs https://www.zidooka.com/archives/4436
  node scripts/ping-indexnow.mjs https://www.zidooka.com/archives/4437`);
  process.exit(0);
}

const host = new URL(siteUrl).host;
const payload = { host, key, urlList: urls };
const endpoints = [
  'https://www.bing.com/indexnow',
  'https://api.indexnow.org/indexnow',
];

console.log(`IndexNow ping for ${host}`);
console.log(`Key: ${key}`);
console.log(`URLs (${urls.length}):`);
for (const url of urls) {
  console.log(`  ${url}`);
}
console.log('');

for (const ep of endpoints) {
  try {
    const res = await fetch(ep, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const ok = res.status === 200 || res.status === 202;
    console.log(`  ${ep} -> ${ok ? 'OK' : `${res.status} ${res.statusText}`}`);
  } catch (e) {
    console.log(`  ${ep} -> ERROR: ${e.message}`);
  }
}
