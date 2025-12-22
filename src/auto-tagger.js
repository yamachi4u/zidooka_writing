import { WpClient } from './services/wpClient.js';

async function main() {
  const wp = new WpClient();
  const dryRun = process.argv.includes('--dry-run');

  console.log('Fetching all tags...');
  const allTags = await wp.getTags();
  console.log(`Found ${allTags.length} tags.`);

  console.log('Fetching all posts...');
  // Note: For a huge site, we should loop through pages. 
  // For now, fetching first 100 posts.
  const { data: posts } = await wp.getPosts(1, 100);
  console.log(`Found ${posts.length} posts.`);

  for (const post of posts) {
    const content = (post.title.rendered + ' ' + post.content.rendered).toLowerCase();
    const currentTagIds = post.tags || [];
    const newTagIds = new Set(currentTagIds);
    const addedTagNames = [];

    // Check each tag
    for (const tag of allTags) {
      if (content.includes(tag.name.toLowerCase())) {
        if (!newTagIds.has(tag.id)) {
          newTagIds.add(tag.id);
          addedTagNames.push(tag.name);
        }
      }
    }

    if (addedTagNames.length > 0) {
      console.log(`\n[${post.id}] "${post.title.rendered}"`);
      console.log(`  Found keywords: ${addedTagNames.join(', ')}`);
      
      if (!dryRun) {
        await wp.updatePost(post.id, { tags: Array.from(newTagIds) });
        console.log('  -> Updated.');
      } else {
        console.log('  -> (Dry Run) Would update.');
      }
    }
  }

  console.log('\nDone!');
}

main();
