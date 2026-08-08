import { PostService } from './services/postService.js';
import { WpClient } from './services/wpClient.js';
import { execFileSync } from 'child_process';
import fs from 'fs/promises';
import { fileURLToPath } from 'url';
import path from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const args = process.argv.slice(2);
const command = args[0];

async function pathExists(filePath) {
  try {
    await fs.access(filePath);
    return true;
  } catch {
    return false;
  }
}

async function resolveBilingualPairPaths(inputPath) {
  const absoluteInput = path.resolve(inputPath);
  const dir = path.dirname(absoluteInput);
  const base = path.basename(absoluteInput);
  const candidates = [];

  if (/-jp\.md$/i.test(base)) {
    candidates.push(base.replace(/-jp\.md$/i, '-en.md'));
  } else if (/-ja\.md$/i.test(base)) {
    candidates.push(base.replace(/-ja\.md$/i, '-en.md'));
  } else if (/-en\.md$/i.test(base)) {
    candidates.push(
      base.replace(/-en\.md$/i, '-jp.md'),
      base.replace(/-en\.md$/i, '-ja.md')
    );
  } else {
    throw new Error('Bilingual posting expects a filename ending in `-jp.md`, `-ja.md`, or `-en.md`.');
  }

  for (const candidate of candidates) {
    const candidatePath = path.join(dir, candidate);
    if (await pathExists(candidatePath)) {
      return [absoluteInput, candidatePath];
    }
  }

  throw new Error(`Pair draft not found for ${inputPath}. Expected one of: ${candidates.join(', ')}`);
}

async function postBilingualPair(inputPath, { requestedDate = '', forceNow = false } = {}) {
  const [firstPath, secondPath] = await resolveBilingualPairPaths(inputPath);
  const pairPoster = new PostService();
  let overrides = forceNow
    ? { status: 'publish', ignoreFrontmatterSchedule: true }
    : {};

  if (requestedDate) {
    const scheduledDate = pairPoster.normalizeScheduleDate(requestedDate);
    console.log(`Scheduling bilingual pair for: ${scheduledDate}`);
    overrides = { status: 'future', date: scheduledDate };
  }

  const results = [];
  for (const targetPath of [firstPath, secondPath]) {
    const pairResult = await pairPoster.post(targetPath, overrides);
    console.log(`Successfully posted: "${pairResult.title.raw}"`);
    console.log(`Link: ${pairResult.link}`);
    results.push(pairResult);
  }
  return results;
}

async function main() {
  try {
    switch (command) {
      case 'auth':
        const wp = new WpClient();
        console.log('Checking authentication...');
        const authResult = await wp.checkAuth();
        if (authResult.success) {
          console.log(`Authenticated: ${authResult.user.name} (ID: ${authResult.user.id})`);
        } else {
          console.error('Authentication failed:', authResult.error);
        }
        break;

      case 'sync':
        const service = new PostService();
        await service.syncMetadata();
        break;

      case 'list':
        const listService = new PostService();
        const type = args[1]; // 'categories' or 'tags'
        const metadata = await listService.loadMetadata();

        if (type === 'categories' || type === 'cat') {
          console.log('--- Categories ---');
          metadata.categories.forEach(c => console.log(`- ${c.name} (slug: ${c.slug}, id: ${c.id})`));
        } else if (type === 'tags') {
          console.log('--- Tags ---');
          metadata.tags.forEach(t => console.log(`- ${t.name} (slug: ${t.slug}, id: ${t.id})`));
        } else {
          console.log('Usage: node src/index.js list [categories|tags]');
        }
        break;

      case 'post':
        const filePath = args[1];
        if (!filePath) throw new Error('Please specify a file path');

        const poster = new PostService();
        const result = await poster.post(filePath);
        console.log(`Successfully posted: "${result.title.raw}"`);
        console.log(`Link: ${result.link}`);
        break;

      case 'post-pair': {
        const pairPath = args[1];
        if (!pairPath) throw new Error('Please specify one Japanese or English draft path');
        await postBilingualPair(pairPath, { forceNow: args.includes('--now') });
        break;
      }

      case 'schedule-pair': {
        const pairPath = args[1];
        if (!pairPath) throw new Error('Please specify one Japanese or English draft path');

        const scheduler = new PostService();
        const requestedDate = args.slice(2).join(' ').trim();
        const scheduledDate = requestedDate
          ? scheduler.normalizeScheduleDate(requestedDate)
          : await scheduler.findNextScheduleSlot();
        await postBilingualPair(pairPath, { requestedDate: scheduledDate });
        break;
      }

      case 'schedule':
        const schedulePath = args[1];
        if (!schedulePath) throw new Error('Please specify a file path');

        const scheduler = new PostService();
        const requestedDate = args.slice(2).join(' ').trim();
        const scheduleResult = await scheduler.schedulePost(schedulePath, requestedDate);
        console.log(`Successfully scheduled: "${scheduleResult.title.raw}"`);
        console.log(`Link: ${scheduleResult.link}`);
        break;

      case 'thumbnail': {
        // Delegate to the CJS thumbnail generator script
        const thumbScript = path.resolve(__dirname, '..', 'scripts', 'generate-thumbnail.cjs');
        const thumbArgs = args.slice(1); // Pass all args after "thumbnail"
        if (thumbArgs.length === 0 || thumbArgs.includes('--help')) {
          thumbArgs.push('--help');
        }
        try {
          execFileSync('node', [thumbScript, ...thumbArgs], { stdio: 'inherit' });
        } catch (e) {
          // execFileSync throws on non-zero exit; error already printed by child
          process.exit(e.status || 1);
        }
        break;
      }

      default:
        console.log(`
ZIDOOKA CLI Tool (Refactored)
Usage:
  node src/index.js sync             - Sync WP categories/tags
  node src/index.js list <type>      - List categories or tags (type: categories, tags)
  node src/index.js post <file>      - Post markdown file to WP
  node src/index.js post-pair <file> [--now] - Post paired drafts; --now ignores publish_at
  node src/index.js schedule <file> [date]      - Schedule one post (date optional)
  node src/index.js schedule-pair <file> [date] - Schedule a bilingual pair together
  node src/index.js auth             - Check authentication
  node src/index.js thumbnail [opts] - Generate a branded thumbnail image

Thumbnail options (see --help):
  node src/index.js thumbnail --title "..." --output path.png [--subtitle "..." --accent cyan --category "..."]

Frontmatter tips:
  - Custom post types: set \`post_type: gas_script\` (and \`gas:\` meta) to publish GAS distribution posts.
  - Scheduled posts: set \`publish_at: "YYYY-MM-DD HH:mm"\` in the WordPress timezone.
        `);
    }
  } catch (error) {
    console.error('Error:', error.message);
    if (error.response) {
      console.error('API Response:', error.response.data);
    }
  }
}

main();
