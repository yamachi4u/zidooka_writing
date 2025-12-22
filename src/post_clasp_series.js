import fs from 'fs/promises';
import path from 'path';
import { PostService } from './services/postService.js';

const dir = path.join(process.cwd(), 'drafts', 'clasp-ai-gas');

async function main() {
  const service = new PostService();
  const files = await fs.readdir(dir);
  
  for (const file of files) {
    if (!file.endsWith('.md')) continue;
    
    const filePath = path.join(dir, file);
    console.log(`Posting ${file}...`);
    try {
      const result = await service.post(filePath);
      console.log(`✅ Posted: ${result.title.raw} (${result.link})`);
    } catch (e) {
      console.error(`❌ Failed to post ${file}:`, e.message);
    }
  }
}

main();
