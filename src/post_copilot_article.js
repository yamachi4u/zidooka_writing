import { PostService } from './services/postService.js';
import path from 'path';

async function main() {
  const service = new PostService();
  const base = path.join(process.cwd(), 'drafts');
  const files = [
    path.join(base, 'copilot-premium-request-allowance-jp.md'),
    path.join(base, 'copilot-premium-request-allowance-en.md')
  ];

  for (const f of files) {
    console.log(`Posting ${f}...`);
    try {
      const res = await service.post(f);
      console.log(`Posted: ${res.title && (res.title.rendered || res.title.raw || res.title)} -> ${res.link}`);
    } catch (e) {
      console.error(`Failed to post ${f}:`, e.message || e);
    }
  }
}

main();
