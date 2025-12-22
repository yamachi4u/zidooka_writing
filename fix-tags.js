import { WpClient } from './src/wp.js';

const POST_ID = 1266;
const TAGS_TO_ADD = ["VS Code", "Node.js", "Automation"];

async function main() {
  const wp = new WpClient();
  const tagIds = [];

  console.log(`Adding tags to Post ${POST_ID}: ${TAGS_TO_ADD.join(', ')}`);

  // 1. Get existing tags to check against
  const existingTags = await wp.getTags();

  for (const tagName of TAGS_TO_ADD) {
    let tag = existingTags.find(t => t.name.toLowerCase() === tagName.toLowerCase());
    
    if (!tag) {
      console.log(`Tag "${tagName}" not found. Creating...`);
      tag = await wp.createTag(tagName);
    } else {
      console.log(`Tag "${tagName}" exists (ID: ${tag.id}).`);
    }
    tagIds.push(tag.id);
  }

  // 2. Update the post
  await wp.updatePost(POST_ID, { tags: tagIds });
  console.log('Done! Tags updated.');
}

main();
