import fs from 'fs/promises';
import path from 'path';
import axios from 'axios';

import { WpClient } from '../src/services/wpClient.js';

function parseArgs(argv) {
  const args = {};
  for (let index = 0; index < argv.length; index += 1) {
    const value = argv[index];
    if (!value.startsWith('--')) continue;
    const key = value.slice(2);
    if (key === 'apply') {
      args.apply = true;
    } else {
      args[key] = argv[index + 1];
      index += 1;
    }
  }
  return args;
}

function usage() {
  console.log('Usage: node scripts/update-post-seo.mjs --id <post-id> [--title "..."] [--excerpt "..."] [--apply]');
}

async function main() {
  const args = parseArgs(process.argv.slice(2));
  const postId = Number(args.id);
  if (!postId || (!args.title && !args.excerpt)) {
    usage();
    process.exitCode = 1;
    return;
  }

  const wp = new WpClient();
  const currentResponse = await axios.get(`${wp.baseUrl}/wp/v2/posts/${postId}?context=edit`, {
    headers: wp.authHeader,
    timeout: wp.timeout,
  });
  const current = currentResponse.data;
  const changes = {};
  if (args.title && args.title !== current.title.raw) changes.title = args.title;
  if (args.excerpt && args.excerpt !== current.excerpt.raw) changes.excerpt = args.excerpt;

  console.log(`Post ${postId}: ${current.link}`);
  console.log(`Current title: ${current.title.raw}`);
  console.log(`Proposed title: ${changes.title ?? '(unchanged)'}`);
  console.log(`Current excerpt: ${current.excerpt.raw || '(empty)'}`);
  console.log(`Proposed excerpt: ${changes.excerpt ?? '(unchanged)'}`);

  if (Object.keys(changes).length === 0) {
    console.log('No changes required.');
    return;
  }
  if (!args.apply) {
    console.log('Dry run only. Pass --apply to update WordPress.');
    return;
  }

  const backupDir = path.join(process.cwd(), 'tmp_remote_agent', 'wp-post-backups');
  await fs.mkdir(backupDir, { recursive: true });
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
  const backupPath = path.join(backupDir, `${postId}-${timestamp}.json`);
  await fs.writeFile(backupPath, JSON.stringify(current, null, 2), 'utf8');

  const updated = await wp.updatePost(postId, changes);
  console.log(`Backup: ${backupPath}`);
  console.log(`Updated: ${updated.link}`);
  console.log(`Title: ${updated.title.rendered}`);
}

main().catch((error) => {
  console.error(error.response?.data ?? error.message);
  process.exitCode = 1;
});
