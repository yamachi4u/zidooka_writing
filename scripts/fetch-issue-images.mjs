#!/usr/bin/env node
/**
 * GitHub Issue の添付画像を取得して記事用アセットとして配置する
 *
 * スマホでChatGPTに貼ったスクリーンショットを GitHub Issue に添付し、
 * このスクリプトでローカルへ取得して `images/YYYY/<slug>/` へ配置する。
 *
 * 使い方:
 *   node scripts/fetch-issue-images.mjs <issue-number> [--slug <slug>] [--dir <target-dir>] [--dry-run]
 *
 * 依存:
 *   - gh CLI が認証済みであること (gh auth status)
 *   - issue 本文・コメントに Markdown 画像リンクが含まれていること
 *
 * プライバシー:
 *   取得後、EXIF除去・命名・alt作成は downstream 工程で実施する。
 *   公開前に必ず docs/operations/phone-image-pipeline.md のチェックリストを通すこと。
 */

import { execFileSync } from 'child_process';
import { mkdirSync, writeFileSync, readFileSync, existsSync } from 'fs';
import { fileURLToPath } from 'url';
import path from 'path';
import crypto from 'crypto';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT = path.resolve(__dirname, '..');

const args = process.argv.slice(2);
const flagValue = (flag) => {
  const i = args.indexOf(flag);
  return i >= 0 ? args[i + 1] : undefined;
};

const issueNumber = args[0];
const slug = flagValue('--slug');
const targetDir = flagValue('--dir');
const dryRun = args.includes('--dry-run');

if (!issueNumber) {
  console.error('Usage: node scripts/fetch-issue-images.mjs <issue-number> [--slug <slug>] [--dir <target-dir>] [--dry-run]');
  process.exit(1);
}

function ghJson(cmd) {
  return JSON.parse(execFileSync('gh', ['api', ...cmd], { encoding: 'utf8', stdio: ['pipe', 'pipe', 'inherit'] }));
}

function extractImageUrls(markdown) {
  const urls = new Set();
  const re = /!\[[^\]]*\]\((https?:\/\/[^)]+)\)/g;
  let m;
  while ((m = re.exec(markdown)) !== null) urls.add(m[1]);
  const re2 = /(https:\/\/private-user-images\.githubusercontent\.com\/[^\s\)]+)/g;
  while ((m = re2.exec(markdown)) !== null) urls.add(m[1]);
  return [...urls];
}

async function download(url, outPath) {
  const res = await fetch(url, { redirect: 'follow' });
  if (!res.ok) throw new Error(`HTTP ${res.status} for ${url}`);
  const buf = Buffer.from(await res.arrayBuffer());
  writeFileSync(outPath, buf);
  return buf.length;
}

function safeName(raw) {
  const base = raw.replace(/[^\w.-]+/g, '-').replace(/^[-.]+|[-.]+$/g, '').toLowerCase();
  return base || `asset-${Date.now()}`;
}

// --- resolve issue ---
const issue = ghJson(['repos/{owner}/{repo}/issues', issueNumber]);
const owner = issue.repository_url.split('/').slice(-2, -1)[0];
const repo = issue.repository_url.split('/').slice(-1)[0];

const body = issue.body || '';
let texts = [body];
const comments = ghJson([`repos/{owner}/{repo}/issues/${issueNumber}/comments`, '--paginate']);
for (const c of comments) texts.push(c.body || '');
const commenters = comments.map((c) => c.user.login);

const urls = [...new Set(texts.flatMap(extractImageUrls))];
if (urls.length === 0) {
  console.error('Issue 本文・コメントに画像リンクが見つかりませんでした。');
  console.error('添付は private-user-images.githubusercontent.com の Markdown リンク形式で本文に貼ってください。');
  process.exit(1);
}

// --- target dir ---
const year = new Date().getFullYear();
const resolvedSlug = slug || issue.title.toLowerCase().replace(/[^\w]+/g, '-').replace(/^-|-$/g, '') || `issue-${issueNumber}`;
const outDir = targetDir || path.join(ROOT, 'images', String(year), resolvedSlug);

if (dryRun) {
  console.log(`[dry-run] issue #${issueNumber}: ${issue.title}`);
  console.log(`[dry-run] target: ${path.relative(ROOT, outDir)}`);
  for (const u of urls) console.log(`[dry-run]   ${u}`);
  console.log(`[dry-run] image count: ${urls.length}`);
  console.log(`[dry-run] commenters: ${commenters.join(', ')}`);
  process.exit(0);
}

mkdirSync(outDir, { recursive: true });

const results = [];
for (let i = 0; i < urls.length; i++) {
  const url = urls[i];
  const pathname = new URL(url).pathname;
  const ext = path.extname(pathname).toLowerCase() || '.png';
  const outPath = path.join(outDir, `${String(i + 1).padStart(2, '0')}-${safeName(path.basename(pathname, ext))}${ext}`);
  try {
    const bytes = await download(url, outPath);
    results.push({ url, outPath: path.relative(ROOT, outPath), bytes });
    console.log(`✔ ${path.relative(ROOT, outPath)} (${bytes} bytes)`);
  } catch (e) {
    console.error(`✘ ${url} — ${e.message}`);
  }
}

// --- manifest ---
const manifest = {
  issue: `#${issueNumber}`,
  title: issue.title,
  fetched_at: new Date().toISOString(),
  source: issue.html_url,
  commenters,
  files: results.map((r) => ({ file: r.outPath, bytes: r.bytes, source: r.url })),
  alt_suggestions: results.map((r, i) => ({ file: r.outPath, alt: `Issue #${issueNumber} から取得した画像 ${i + 1}` })),
  privacy_check_required: true,
};
writeFileSync(path.join(outDir, '_manifest.json'), JSON.stringify(manifest, null, 2));
console.log(`✔ ${path.relative(ROOT, path.join(outDir, '_manifest.json'))} (manifest)`);
console.log('\n公開前に docs/operations/phone-image-pipeline.md のプライバシーチェックを通してください。');
