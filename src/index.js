import { PostService } from './services/postService.js';
import { WpClient } from './services/wpClient.js';

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

      default:
        console.log(`
ZIDOOKA CLI Tool (Refactored)
Usage:
  node src/index.js sync           - Sync WP categories/tags
  node src/index.js list <type>    - List categories or tags (type: categories, tags)
  node src/index.js post <file>    - Post markdown file to WP
  node src/index.js schedule <file> - Schedule post for next available morning
  node src/index.js auth           - Check authentication

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
