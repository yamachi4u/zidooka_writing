import axios from 'axios';
import { WpClient } from '../src/services/wpClient.js';

const slugs = process.argv.slice(2).flatMap(v => String(v).split(',')).map(v => v.trim()).filter(Boolean);
if (slugs.length === 0) {
  console.error('Usage: node scripts/verify-wordpress-thumbnail.mjs <slug> [slug...]');
  process.exit(2);
}

const wp = new WpClient();
let failed = false;

for (const slug of slugs) {
  const post = await wp.getPostBySlug(slug);
  if (!post) {
    console.error(`FAIL ${slug}: post not found`);
    failed = true;
    continue;
  }

  const mediaId = Number(post.featured_media || 0);
  if (!mediaId) {
    console.error(`FAIL ${slug}: featured_media is 0`);
    failed = true;
    continue;
  }

  try {
    const res = await axios.get(`${wp.baseUrl}/wp/v2/media/${mediaId}`, {
      headers: wp.authHeader,
      timeout: wp.timeout
    });
    const url = res.data?.source_url || '(no source_url)';
    const mime = res.data?.mime_type || '(unknown mime)';
    console.log(`OK ${slug}: post=${post.id} featured_media=${mediaId} mime=${mime}`);
    console.log(`MEDIA ${url}`);
  } catch (error) {
    console.error(`FAIL ${slug}: featured media ${mediaId} could not be fetched (${error.message})`);
    failed = true;
  }
}

if (failed) process.exit(1);
