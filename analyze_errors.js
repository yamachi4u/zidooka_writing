
import fs from 'fs/promises';
import path from 'path';

async function analyzePosts() {
  const dataPath = path.join(process.cwd(), 'data', 'all_posts.json');
  const rawData = await fs.readFile(dataPath, 'utf-8');
  const posts = JSON.parse(rawData);

  const gasKeywords = ['gas', 'google apps script', 'clasp'];
  const chatgptKeywords = ['chatgpt', 'openai', 'gpt'];
  const errorKeywords = ['error', 'エラー', '403', 'timeout', 'crash', 'fail', '止まる', '動かない', '使えない'];

  const gasPosts = { jp: [], en: [] };
  const chatgptPosts = { jp: [], en: [] };

  posts.forEach(post => {
    const title = post.title.toLowerCase();
    const slug = post.slug.toLowerCase();
    
    // Detect Language
    const isEn = slug.endsWith('-en') || /^[a-z0-9-]+$/.test(slug) && !title.match(/[^\x01-\x7E]/); // Rough heuristic
    // Better heuristic: Zidooka usually uses -en suffix for English. If not present, assume JP or check title.
    // Actually, let's rely on slug suffix '-en' for EN, and everything else as JP for now, 
    // or check if title contains Japanese characters.
    const hasJapanese = title.match(/[^\x01-\x7E]/);
    const lang = (slug.endsWith('-en') || !hasJapanese) ? 'en' : 'jp';

    // Check GAS
    if (gasKeywords.some(k => title.includes(k) || slug.includes(k))) {
      if (errorKeywords.some(k => title.includes(k) || slug.includes(k))) {
        if (lang === 'en') gasPosts.en.push(post);
        else gasPosts.jp.push(post);
      }
    }

    // Check ChatGPT
    if (chatgptKeywords.some(k => title.includes(k) || slug.includes(k))) {
      if (errorKeywords.some(k => title.includes(k) || slug.includes(k))) {
        if (lang === 'en') chatgptPosts.en.push(post);
        else chatgptPosts.jp.push(post);
      }
    }
  });

  console.log('--- GAS Errors (JP) ---');
  gasPosts.jp.forEach(p => console.log(`- [${p.title}](/${p.slug})`));
  console.log('\n--- GAS Errors (EN) ---');
  gasPosts.en.forEach(p => console.log(`- [${p.title}](/${p.slug})`));

  console.log('\n--- ChatGPT Errors (JP) ---');
  chatgptPosts.jp.forEach(p => console.log(`- [${p.title}](/${p.slug})`));
  console.log('\n--- ChatGPT Errors (EN) ---');
  chatgptPosts.en.forEach(p => console.log(`- [${p.title}](/${p.slug})`));
}

analyzePosts();
