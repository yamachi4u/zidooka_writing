import { WpClient } from './services/wpClient.js';
import fs from 'fs/promises';
import path from 'path';
import matter from 'gray-matter';

const draftsDir = 'drafts/clasp-ai-gas';

async function main() {
  const wp = new WpClient();
  console.log('Fetching posts...');
  
  // Fetch enough posts to cover the series (we just posted ~22 posts)
  const { data: posts } = await wp.getPosts(1, 100);
  
  // Create a map of slug -> link
  const slugMap = {};
  posts.forEach(post => {
    slugMap[post.slug] = post.link;
  });

  console.log(`Found ${Object.keys(slugMap).length} posts.`);
  // console.log('Slug Map:', slugMap); // Debug

  // Map filenames to slugs (based on generate_series.cjs logic)
  // We can also just read the frontmatter of each file to get its slug, 
  // but we need to know the slug of the *target* link.
  
  // The links in the markdown are currently like: ./01-ch01-jp.md
  // We need to map "01-ch01-jp.md" -> "clasp-gas-beginner-ch01-jp" -> URL
  
  // Let's build a map of filename -> slug by reading all files first
  const files = await fs.readdir(draftsDir);
  const filenameToSlug = {};
  
  for (const file of files) {
    if (!file.endsWith('.md')) continue;
    const content = await fs.readFile(path.join(draftsDir, file), 'utf-8');
    const { data } = matter(content);
    if (data.slug) {
      filenameToSlug[file] = data.slug;
    }
  }
  console.log('Filename to Slug Map:', filenameToSlug);

  // Now iterate and replace
  for (const file of files) {
    if (!file.endsWith('.md')) continue;
    
    const filePath = path.join(draftsDir, file);
    let content = await fs.readFile(filePath, 'utf-8');
    let changed = false;

    // Regex to find markdown links: [Title](./filename.md)
    // We want to capture the filename
    const linkRegex = /\[(.*?)\]\(\.\/(.*?\.md)\)/g;
    
    content = content.replace(linkRegex, (match, title, targetFilename) => {
      const targetSlug = filenameToSlug[targetFilename];
      if (targetSlug && slugMap[targetSlug]) {
        console.log(`Linking ${targetFilename} -> ${slugMap[targetSlug]}`);
        changed = true;
        return `[${title}](${slugMap[targetSlug]})`;
      } else {
        console.warn(`Could not resolve link for ${targetFilename} (Slug: ${targetSlug})`);
        // Try to find slug in map even if it might be slightly different?
        // No, let's stick to exact match first.
        return match;
      }
    });

    if (changed) {
      await fs.writeFile(filePath, content);
      console.log(`Updated ${file}`);
    }
  }
}

main();
