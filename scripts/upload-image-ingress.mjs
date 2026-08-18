#!/usr/bin/env node

import fs from 'node:fs';
import path from 'node:path';
import sharp from 'sharp';

function arg(name) {
  const prefix = `--${name}=`;
  const found = process.argv.find((value) => value.startsWith(prefix));
  if (found) return found.slice(prefix.length);
  return process.argv.includes(`--${name}`) ? 'true' : null;
}

const filePath = arg('file');
if (!filePath) {
  throw new Error('Usage: node scripts/upload-image-ingress.mjs --file=path/to/image.jpg [--post-slug=slug] [--post-id=123] [--alt=text] [--max-dim=2000] [--quality=85]');
}

const baseUrl = (process.env.WP_SITE_URL || 'https://www.zidooka.com').replace(/\/$/, '');
const endpoint = process.env.WP_IMAGE_INGRESS_URL || `${baseUrl}/wp-json/zidooka/v1/image`;
const user = process.env.WP_USER || process.env.WP_USERNAME;
const password = process.env.WP_APP_PASSWORD || process.env.WP_PASSWORD;

if (!user || !password) {
  throw new Error('WP_USER/WP_USERNAME and WP_APP_PASSWORD/WP_PASSWORD are required.');
}

const absolute = path.resolve(filePath);
const maxDim = Number(arg('max-dim') || '2000');
const quality = Number(arg('quality') || '85');

/**
 * Normalize photo before upload: preserve source quality, never upscale.
 * - Applies EXIF orientation (auto-rotate).
 * - Resizes to the target long edge (~1600-2000px) when larger.
 * - Re-encodes to JPEG/WebP quality 85 by default.
 * - Skips already-suitable images.
 */
async function prepareImage() {
  const name = path.basename(absolute);
  let meta;
  try {
    meta = await sharp(absolute).metadata();
  } catch {
    return { buffer: fs.readFileSync(absolute), name, processed: false, reason: 'unreadable-or-unsupported' };
  }

  const format = meta.format;
  const longestEdge = Math.max(meta.width || 0, meta.height || 0);
  const alreadySuitable = format !== 'jpeg' && format !== 'webp' && format !== 'png';

  if (alreadySuitable || (longestEdge <= maxDim && (meta.size ?? 0) <= 900000)) {
    return { buffer: fs.readFileSync(absolute), name, processed: false, reason: 'already-suitable' };
  }

  const pipeline = sharp(absolute, { failOn: 'none' }).rotate();
  if (longestEdge > maxDim) {
    pipeline.resize({ width: maxDim, height: maxDim, fit: 'inside', withoutEnlargement: true });
  }
  if (format === 'jpeg') {
    pipeline.jpeg({ quality, mozjpeg: true });
  } else if (format === 'webp') {
    pipeline.webp({ quality });
  } else if (format === 'png') {
    pipeline.png({ quality, compressionLevel: 9 });
  }

  const buffer = await pipeline.toBuffer();
  const outMeta = await sharp(buffer).metadata();
  return {
    buffer,
    name,
    processed: true,
    from: { width: meta.width, height: meta.height, bytes: meta.size, format },
    to: { width: outMeta.width, height: outMeta.height, bytes: buffer.length, format: outMeta.format },
  };
}

const { buffer, name, processed, reason, from, to } = await prepareImage();

if (processed) {
  console.log(`[image] processed: ${JSON.stringify({ from, to })}`);
} else if (reason) {
  console.log(`[image] not processed: ${reason}`);
}

if (arg('dry-run')) {
  console.log('[image] dry-run: upload skipped');
  process.exit(0);
}

const form = new FormData();
form.append('file', new Blob([buffer]), name);

const postSlug = arg('post-slug');
const postId = arg('post-id');
const alt = arg('alt');
const title = arg('title');

if (postSlug) form.append('post_slug', postSlug);
if (postId) form.append('post_id', postId);
if (alt) form.append('alt_text', alt);
if (title) form.append('title', title);
form.append('set_featured', arg('set-featured') ?? 'true');

const auth = Buffer.from(`${user}:${password}`).toString('base64');
const response = await fetch(endpoint, {
  method: 'POST',
  headers: { Authorization: `Basic ${auth}` },
  body: form,
});

const text = await response.text();
let payload;
try {
  payload = JSON.parse(text);
} catch {
  payload = { raw: text };
}

if (!response.ok) {
  console.error(JSON.stringify(payload, null, 2));
  process.exit(1);
}

console.log(JSON.stringify(payload, null, 2));
