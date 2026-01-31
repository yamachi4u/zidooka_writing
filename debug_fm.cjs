
const fs = require('fs');
const matter = require('gray-matter');

const filePath = 'c:\\Users\\user\\Documents\\zidooka_writing\\drafts\\clawdbot-sandbox-github-flow-2026.md';
const content = fs.readFileSync(filePath, 'utf-8');
const { data, content: body } = matter(content);

console.log('Frontmatter:', data);
console.log('Slug:', data.slug);
console.log('Title:', data.title);
