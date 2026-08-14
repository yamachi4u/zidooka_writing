#!/usr/bin/env node

import fs from 'node:fs';
import path from 'node:path';

function arg(name) {
  const prefix = `--${name}=`;
  const found = process.argv.find((value) => value.startsWith(prefix));
  return found ? found.slice(prefix.length) : null;
}

const filePath = arg('file');
if (!filePath) {
  throw new Error('Usage: node scripts/upload-image-ingress.mjs --file=path/to/image.jpg [--post-slug=slug] [--post-id=123] [--alt=text]');
}

const baseUrl = (process.env.WP_SITE_URL || 'https://www.zidooka.com').replace(/\/$/, '');
const endpoint = process.env.WP_IMAGE_INGRESS_URL || `${baseUrl}/wp-json/zidooka/v1/image`;
const user = process.env.WP_USER || process.env.WP_USERNAME;
const password = process.env.WP_APP_PASSWORD || process.env.WP_PASSWORD;

if (!user || !password) {
  throw new Error('WP_USER/WP_USERNAME and WP_APP_PASSWORD/WP_PASSWORD are required.');
}

const absolute = path.resolve(filePath);
const bytes = fs.readFileSync(absolute);
const form = new FormData();
form.append('file', new Blob([bytes]), path.basename(absolute));

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
  headers: {
    Authorization: `Basic ${auth}`,
  },
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
