import { WpClient } from './services/wpClient.js';

async function main() {
  const wp = new WpClient();
  const slugs = [
    'you-have-exceeded-your-premium-request-allowance-jp',
    'you-have-exceeded-your-premium-request-allowance-en'
  ];
  for (const slug of slugs) {
    const post = await wp.getPostBySlug(slug);
    if (post) {
      console.log(`${slug} -> ID:${post.id} Title:${post.title && (post.title.rendered || post.title.raw)} Link:${post.link} Status:${post.status}`);
    } else {
      console.log(`${slug} -> NOT FOUND`);
    }
  }
}

main();
