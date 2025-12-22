
import { WpClient } from './services/wpClient.js';
import fs from 'fs/promises';
import path from 'path';
import axios from 'axios';

async function fetchPostById(id) {
  const wp = new WpClient();
  try {
    console.log(`Fetching post ID: ${id}...`);
    const res = await axios.get(`${wp.baseUrl}/wp/v2/posts/${id}`, { headers: wp.authHeader });
    const post = res.data;

    // Convert HTML content to Markdown-like text (simple conversion)
    // For now, we just dump the content. In a real scenario, we might want a proper converter.
    // But since the user wants to "edit" it, we'll just save the raw content or a simple structure.
    // Actually, let's try to make it a valid markdown file for the pipeline.
    
    // Fetch category/tag names
    const categories = await wp.getCategories();
    const tags = await wp.getTags();
    
    const postCategories = post.categories.map(id => {
      const cat = categories.find(c => c.id === id);
      return cat ? cat.slug : id;
    });

    const postTags = post.tags.map(id => {
      const tag = tags.find(t => t.id === id);
      return tag ? tag.name : id;
    });

    const frontmatter = `---
title: "${post.title.rendered}"
slug: ${post.slug}
date: ${post.date}
categories: 
${postCategories.map(c => `  - ${c}`).join('\n')}
tags: 
${postTags.map(t => `  - ${t}`).join('\n')}
status: publish
id: ${post.id}
---

${post.content.rendered}
`;

    const outputPath = path.join(process.cwd(), 'drafts', `${post.slug}.md`);
    await fs.writeFile(outputPath, frontmatter);
    console.log(`Saved to ${outputPath}`);

  } catch (error) {
    console.error('Error fetching post:', error.message);
    if (error.response) console.error(error.response.data);
  }
}

const id = process.argv[2];
if (id) {
  fetchPostById(id);
} else {
  console.log('Usage: node src/fetch_post.js <id>');
}
