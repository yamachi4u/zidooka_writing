import fs from 'fs/promises';
import path from 'path';
import matter from 'gray-matter';
import axios from 'axios';
import { WpClient } from '../src/services/wpClient.js';

function parseArgs(argv) {
  const args = {};
  for (let i = 0; i < argv.length; i += 1) {
    const part = argv[i];
    if (!part.startsWith('--')) continue;
    const [key, ...rest] = part.slice(2).split('=');
    if (rest.length > 0) {
      args[key] = rest.join('=');
      continue;
    }
    const next = argv[i + 1];
    if (!next || next.startsWith('--')) {
      args[key] = true;
      continue;
    }
    args[key] = next;
    i += 1;
  }
  return args;
}

function splitCsv(value) {
  return String(value || '')
    .split(',')
    .map((item) => item.trim())
    .filter(Boolean);
}

function yamlValue(value) {
  return JSON.stringify(value ?? '');
}

function toFrontmatter(post) {
  return [
    '---',
    `id: ${post.id}`,
    `slug: ${yamlValue(post.slug || '')}`,
    `title: ${yamlValue(post.title?.raw || post.title?.rendered || '')}`,
    `status: ${yamlValue(post.status || 'publish')}`,
    `date: ${yamlValue(post.date || '')}`,
    `excerpt: ${yamlValue(post.excerpt?.raw || '')}`,
    `link: ${yamlValue(post.link || '')}`,
    'raw_html: true',
    '---',
    '',
  ].join('\n');
}

async function fetchPostRaw(wp, id) {
  const res = await axios.get(`${wp.baseUrl}/wp/v2/posts/${id}?context=edit`, {
    headers: wp.authHeader,
    timeout: wp.timeout,
  });
  return res.data;
}

async function exportPosts({ ids, outDir }) {
  const wp = new WpClient();
  await fs.mkdir(outDir, { recursive: true });

  for (const id of ids) {
    const post = await fetchPostRaw(wp, id);
    const filename = `${String(post.id).padStart(4, '0')}-${post.slug}.md`;
    const outPath = path.join(outDir, filename);
    const body = post.content?.raw || '';
    await fs.writeFile(outPath, `${toFrontmatter(post)}${body}`, 'utf8');
    console.log(`Exported ${post.id} -> ${outPath}`);
  }
}

async function updatePostFromFile(filePath, dryRun = false) {
  const wp = new WpClient();
  const raw = await fs.readFile(filePath, 'utf8');
  const { data, content } = matter(raw);

  if (!data.id) {
    throw new Error(`Missing 'id' in frontmatter: ${filePath}`);
  }

  const payload = {
    title: data.title,
    slug: data.slug,
    status: data.status || 'publish',
    date: data.date || undefined,
    excerpt: data.excerpt || '',
    content,
  };

  if (dryRun) {
    console.log(`[dry-run] Would update post ${data.id}: ${data.slug}`);
    return;
  }

  const result = await wp.updatePost(data.id, payload, 'posts');
  console.log(`Updated ${result.id}: ${result.link}`);
}

function usage() {
  console.log(`Usage:
  node scripts/wp-raw-post.mjs export --ids=121,633 --out-dir=drafts/raw-seo-fixes
  node scripts/wp-raw-post.mjs update --file=drafts/raw-seo-fixes/0121-example.md
  node scripts/wp-raw-post.mjs update --file=drafts/raw-seo-fixes/0121-example.md --dry-run`);
}

async function main() {
  const [command] = process.argv.slice(2);
  const args = parseArgs(process.argv.slice(3));

  if (!command || command === '--help' || command === 'help') {
    usage();
    return;
  }

  if (command === 'export') {
    const ids = splitCsv(args.ids || args.id);
    if (!ids.length) {
      throw new Error('Missing --ids');
    }
    await exportPosts({
      ids,
      outDir: path.resolve(process.cwd(), args['out-dir'] || 'drafts/raw-seo-fixes'),
    });
    return;
  }

  if (command === 'update') {
    if (!args.file) {
      throw new Error('Missing --file');
    }
    await updatePostFromFile(path.resolve(process.cwd(), args.file), Boolean(args['dry-run']));
    return;
  }

  throw new Error(`Unknown command: ${command}`);
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
