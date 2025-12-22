import { convertToBlocks } from './src/gutenberg.js';

const markdown = `
# Heading 1

This is a paragraph with **bold** text.

![Image](http://example.com/image.png)

## Heading 2

- List item 1
- List item 2

\`\`\`javascript
console.log('Hello');
\`\`\`
`;

const html = convertToBlocks(markdown, { 'http://example.com/image.png': 123 });
console.log(html);
