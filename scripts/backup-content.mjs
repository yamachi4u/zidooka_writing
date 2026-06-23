import 'dotenv/config';
import { writeFileSync, mkdirSync, existsSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const BASE = process.env.WP_API_URL;
const USER = process.env.WP_USER;
const PASS = process.env.WP_APP_PASSWORD;

if (!BASE || !USER || !PASS) {
  console.error('Missing WP_API_URL, WP_USER, or WP_APP_PASSWORD in .env');
  process.exit(1);
}

const auth = Buffer.from(`${USER}:${PASS}`).toString('base64');
const headers = { Authorization: `Basic ${auth}`, 'Content-Type': 'application/json' };

const now = new Date();
const ds = now.toISOString().slice(0, 10);
const outDir = join(__dirname, '..', 'backups', ds);
if (!existsSync(outDir)) mkdirSync(outDir, { recursive: true });

async function fetchAll(endpoint, label) {
  console.log(`Fetching ${label}...`);
  const items = [];
  let page = 1;
  while (true) {
    const url = `${BASE}/wp/v2/${endpoint}?per_page=100&page=${page}&status=publish&_fields=id,date,modified,slug,title,content,excerpt,categories,tags,meta`;
    const res = await fetch(url, { headers });
    if (!res.ok) {
      if (res.status === 400 && page > 1) break; // no more pages
      console.error(`  Error fetching page ${page}: ${res.status}`);
      break;
    }
    const data = await res.json();
    if (!data.length) break;
    items.push(...data);
    process.stdout.write(`  page ${page}: ${data.length} items (total: ${items.length})\r`);
    if (data.length < 100) break;
    page++;
  }
  console.log(`\n  ${label}: ${items.length} items`);
  return items;
}

async function main() {
  const posts = await fetchAll('posts', 'Posts');
  const pages = await fetchAll('pages', 'Pages');
  const gasScripts = await fetchAll('gas_script', 'GAS Scripts');

  const all = { exported: ds, posts, pages, gas_scripts: gasScripts };
  const file = join(outDir, 'content.json');
  writeFileSync(file, JSON.stringify(all, null, 2), 'utf-8');
  console.log(`\nSaved: ${file}`);
  console.log(`Total: ${posts.length} posts, ${pages.length} pages, ${gasScripts.length} gas_scripts`);
}

main().catch(e => { console.error(e); process.exit(1); });
