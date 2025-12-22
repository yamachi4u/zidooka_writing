import fs from 'fs/promises';
import path from 'path';
import { WpClient } from './services/wpClient.js';

const dir = path.join(process.cwd(), 'drafts', 'clasp-ai-gas');

const jpMapping = {
  '00-intro-jp.md': 'https://www.zidooka.com/archives/1568',
  '00-ch00-jp.md': 'https://www.zidooka.com/archives/1562',
  '01-ch01-jp.md': 'https://www.zidooka.com/archives/1574',
  '02-ch02-jp.md': 'https://www.zidooka.com/archives/1580',
  '03-ch03-jp.md': 'https://www.zidooka.com/archives/1586',
  '04-ch04-jp.md': 'https://www.zidooka.com/archives/1592',
  '05-ch05-jp.md': 'https://www.zidooka.com/archives/1598',
  '06-ch06-jp.md': 'https://www.zidooka.com/archives/1604',
  '07-ch07-jp.md': 'https://www.zidooka.com/archives/1610',
  '08-ch08-jp.md': 'https://www.zidooka.com/archives/1616',
  '09-ch09-jp.md': 'https://www.zidooka.com/archives/1622'
};

const enFiles = [
  '00-intro-en.md',
  '00-ch00-en.md', '01-ch01-en.md', '02-ch02-en.md', '03-ch03-en.md',
  '04-ch04-en.md', '05-ch05-en.md', '06-ch06-en.md', '07-ch07-en.md',
  '08-ch08-en.md', '09-ch09-en.md'
];

async function getEnMapping(wp) {
  const mapping = {};
  console.log('Fetching EN post URLs...');
  
  for (const file of enFiles) {
    // Read slug from file
    const content = await fs.readFile(path.join(dir, file), 'utf-8');
    const match = content.match(/slug: "(.*?)"/);
    if (match) {
      const slug = match[1];
      const post = await wp.getPostBySlug(slug);
      if (post) {
        mapping[file] = post.link;
        console.log(`Found ${file} -> ${post.link}`);
      } else {
        console.warn(`Could not find post for ${file} (slug: ${slug})`);
      }
    }
  }
  return mapping;
}

async function processFiles() {
  const wp = new WpClient();
  const enMapping = await getEnMapping(wp);
  
  const allMapping = { ...jpMapping, ...enMapping };
  
  const files = await fs.readdir(dir);
  
  for (const file of files) {
    if (!file.endsWith('.md')) continue;
    
    const filePath = path.join(dir, file);
    let content = await fs.readFile(filePath, 'utf-8');
    let originalContent = content;

    // Replace links
    for (const [filename, url] of Object.entries(allMapping)) {
      // Replace ./filename.md
      content = content.split(`(./${filename})`).join(`(${url})`);
      // Replace filename.md (without ./)
      content = content.split(`(${filename})`).join(`(${url})`);
    }

    if (content !== originalContent) {
      await fs.writeFile(filePath, content, 'utf-8');
      console.log(`Updated links in ${file}`);
    }
  }
}

processFiles().catch(console.error);
