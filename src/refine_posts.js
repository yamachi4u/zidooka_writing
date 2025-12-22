import fs from 'fs/promises';
import path from 'path';

const dir = 'drafts/clasp-ai-gas';
const files = [
  '00-ch00-jp.md', '01-ch01-jp.md', '02-ch02-jp.md', '03-ch03-jp.md', '04-ch04-jp.md',
  '05-ch05-jp.md', '06-ch06-jp.md', '07-ch07-jp.md', '08-ch08-jp.md', '09-ch09-jp.md'
];

const titles = {
  '00': '第0章：そもそもGASとClaspって何？',
  '01': '第1章：Claspを使う前に知っておく最低限のこと',
  '02': '第2章：Claspを使うための環境を作る',
  '03': '第3章：GoogleアカウントとClaspをつなぐ',
  '04': '第4章：最初のGASプロジェクトを作ってみる',
  '05': '第5章：GASのコードを書いてみる',
  '06': '第6章：push / pull を理解する',
  '07': '第7章：GASを「実際の業務」に使うイメージ',
  '08': '第8章：初心者が必ずハマるポイント集',
  '09': '第9章：次にやるべきこと'
};

const links = {
  '00': 'https://www.zidooka.com/archives/1562',
  '01': 'https://www.zidooka.com/archives/1574',
  '02': 'https://www.zidooka.com/archives/1580',
  '03': 'https://www.zidooka.com/archives/1586',
  '04': 'https://www.zidooka.com/archives/1592',
  '05': 'https://www.zidooka.com/archives/1598',
  '06': 'https://www.zidooka.com/archives/1604',
  '07': 'https://www.zidooka.com/archives/1610',
  '08': 'https://www.zidooka.com/archives/1616',
  '09': 'https://www.zidooka.com/archives/1622'
};

async function processFile(filename) {
  const filePath = path.join(dir, filename);
  let content = await fs.readFile(filePath, 'utf-8');

  // 1. Remove ** (bolding) from the body
  // We do this BEFORE stripping TOC/NextLink to ensure we clean the body text.
  content = content.replace(/\*\*(.*?)\*\*/g, '$1');

  // 2. Strip existing footer elements (Next Link and TOC) to avoid duplication
  // Remove "Next Chapter" link block
  content = content.replace(/\n---\n\[＞＞ .*?\)\n/g, '');
  // Remove TOC block
  content = content.replace(/\n## 連載目次[\s\S]*$/, '');

  // Trim trailing whitespace
  content = content.trim();

  // 3. Generate new footer
  const currentNum = filename.substring(0, 2);
  const nextNum = String(Number(currentNum) + 1).padStart(2, '0');
  
  let nextLinkBlock = '';
  if (titles[nextNum]) {
    const nextTitle = titles[nextNum];
    const nextUrl = links[nextNum];
    nextLinkBlock = `\n\n---\n[＞＞ 次の章：${nextTitle}](${nextUrl})`;
  } else {
    nextLinkBlock = `\n\n---\n[＞＞ 連載トップへ戻る](https://www.zidooka.com/archives/1568)`;
  }

  let tocBlock = `\n\n## 連載目次\n\n`;
  tocBlock += `- [【固定記事】完全初心者のための Clasp × GAS 完全入門（ZIDOOKA！）](https://www.zidooka.com/archives/1568)\n`;
  
  for (let i = 0; i <= 9; i++) {
    const num = String(i).padStart(2, '0');
    const title = titles[num];
    const url = links[num];
    if (num === currentNum) {
      // We keep bold here for the TOC current indicator as it's a UI element
      tocBlock += `- **${title}** (Current)\n`;
    } else {
      tocBlock += `- [${title}](${url})\n`;
    }
  }

  // Combine
  const newContent = content + nextLinkBlock + tocBlock;

  await fs.writeFile(filePath, newContent, 'utf-8');
  console.log(`Processed ${filename}`);
}

async function main() {
  for (const file of files) {
    await processFile(file);
  }
}

main();
