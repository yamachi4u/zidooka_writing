import { PostService } from './services/postService.js';
import { WpClient } from './services/wpClient.js';
import { execFileSync } from 'child_process';
import { fileURLToPath } from 'url';
import path from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const args = process.argv.slice(2);
const command = args[0];

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

      case 'schedule':
        const schedulePath = args[1];
        if (!schedulePath) throw new Error('Please specify a file path');

        const scheduler = new PostService();
        const scheduleResult = await scheduler.schedulePost(schedulePath);
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
  node src/index.js schedule <file>  - Schedule post for next available morning
  node src/index.js auth             - Check authentication
  node src/index.js thumbnail [opts] - Generate a branded thumbnail image

Thumbnail options (see --help):
  node src/index.js thumbnail --title "..." --output path.png [--subtitle "..." --accent cyan --category "..."]

Frontmatter tips:
  - Custom post types: set \`post_type: gas_script\` (and \`gas:\` meta) to publish GAS distribution posts.
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
