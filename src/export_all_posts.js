
import fs from 'fs/promises';
import path from 'path';
import { WpClient } from './services/wpClient.js';
import { PostService } from './services/postService.js';

async function exportAllPosts() {
  const wp = new WpClient();
  const postService = new PostService();
  const metadataPath = path.join(process.cwd(), 'data', 'metadata.json');
  
  // Ensure metadata is loaded/synced
  let metadata;
  try {
    const data = await fs.readFile(metadataPath, 'utf-8');
    metadata = JSON.parse(data);
  } catch (e) {
    console.log('Metadata not found. Syncing...');
    metadata = await postService.syncMetadata();
  }

  const categoryMap = new Map(metadata.categories.map(c => [c.id, c.name]));
  const tagMap = new Map(metadata.tags.map(t => [t.id, t.name]));

  let allPosts = [];
  let page = 1;
  let hasMore = true;

  console.log('Fetching all posts...');

  while (hasMore) {
    try {
      console.log(`Fetching page ${page}...`);
      const { data: posts, totalPages } = await wp.getPosts(page, 100);
      
      const processedPosts = posts.map(post => ({
        title: post.title.rendered,
        date: post.date,
        modified: post.modified,
        categories: post.categories.map(id => categoryMap.get(id) || id),
        slug: post.slug,
        tags: post.tags.map(id => tagMap.get(id) || id)
      }));

      allPosts = allPosts.concat(processedPosts);

      if (page >= totalPages) {
        hasMore = false;
      } else {
        page++;
      }
    } catch (error) {
      console.error(`Error fetching page ${page}:`, error.message);
      hasMore = false;
    }
  }

  console.log(`Total posts fetched: ${allPosts.length}`);

  const outputPath = path.join(process.cwd(), 'data', 'all_posts.json');
  await fs.writeFile(outputPath, JSON.stringify(allPosts, null, 2));
  console.log(`Exported to ${outputPath}`);
}

exportAllPosts().catch(console.error);
