import fs from 'fs/promises';
import path from 'path';
import matter from 'gray-matter';
import { WpClient } from './wpClient.js';
import { ImageProcessor } from './imageProcessor.js';
import { MarkdownConverter } from './markdownConverter.js';
import { config } from '../config/index.js';
import { makeExcerpt, slugify } from '../utils/seo.js';

export class PostService {
  constructor() {
    this.wp = new WpClient();
    this.imageProcessor = new ImageProcessor(this.wp);
  }

  async loadMetadata() {
    try {
      const data = await fs.readFile(config.paths.metadata, 'utf-8');
      return JSON.parse(data);
    } catch (e) {
      console.warn('Metadata not found. Running sync...');
      return await this.syncMetadata();
    }
  }

  async syncMetadata() {
    console.log('Syncing categories and tags...');
    const [categories, tags] = await Promise.all([
      this.wp.getCategories(),
      this.wp.getTags()
    ]);

    const metadata = {
      categories: categories.map(c => ({ id: c.id, name: c.name, slug: c.slug })),
      tags: tags.map(t => ({ id: t.id, name: t.name, slug: t.slug })),
      lastUpdated: new Date().toISOString()
    };

    await fs.mkdir(path.dirname(config.paths.metadata), { recursive: true });
    await fs.writeFile(config.paths.metadata, JSON.stringify(metadata, null, 2));
    return metadata;
  }

  async processFile(filePath) {
    console.log(`Processing ${filePath}...`);
    const fileContent = await fs.readFile(filePath, 'utf-8');
    const { data: frontmatter, content: markdownBody } = matter(fileContent);


    // 1. Normalize emphasis and handle Images
    // Convert Markdown bold to ZIDOOKA inline-strong syntax so the converter maps it properly
    let updatedMarkdown = markdownBody
      .replace(/\*\*(.*?)\*\*/g, '==$1==')
      .replace(/__(.*?)__/g, '==$1==');
    const imageRegex = /!\[(.*?)\]\((.*?)\)/g;
    const matches = [...markdownBody.matchAll(imageRegex)];
    const imageMap = {};
    let firstUploadedImageId = null;

    for (const match of matches) {
      const altText = match[1];
      const localPath = match[2];

      if (!localPath.startsWith('http')) {
        let absoluteImagePath = path.resolve(path.dirname(filePath), decodeURIComponent(localPath));
        
        // Fallback: Try resolving from project root if file doesn't exist relative to markdown
        try {
          await fs.access(absoluteImagePath);
        } catch {
          const rootPath = path.resolve(process.cwd(), decodeURIComponent(localPath));
          try {
            await fs.access(rootPath);
            absoluteImagePath = rootPath;
          } catch {
            // If neither exists, keep original path to let processAndUpload handle the error
          }
        }

        const media = await this.imageProcessor.processAndUpload(absoluteImagePath, altText);
        
        if (media) {
          imageMap[media.source_url] = media.id;
          if (!firstUploadedImageId) firstUploadedImageId = media.id;
          // Replace all occurrences of the local path with uploaded URL
          if (updatedMarkdown.replaceAll) {
            updatedMarkdown = updatedMarkdown.replaceAll(localPath, media.source_url);
          } else {
            const esc = localPath.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            updatedMarkdown = updatedMarkdown.replace(new RegExp(esc, 'g'), media.source_url);
          }
        }
      }
    }

    // 2. Convert to Gutenberg Blocks
    const converter = new MarkdownConverter(imageMap);
    const htmlContent = converter.convertToBlocks(updatedMarkdown);
    const excerpt = frontmatter.excerpt ? makeExcerpt(String(frontmatter.excerpt), 160) : makeExcerpt(updatedMarkdown, 160);

    // 3. Resolve Metadata
    const metadata = await this.loadMetadata();
    const categoryIds = this.resolveCategories(frontmatter.categories, metadata.categories);
    const tagIds = await this.resolveTags(frontmatter.tags, metadata.tags);

    // 4. Handle Featured Image
    let featuredMediaId = null;
    const featuredImagePath = frontmatter.featured_image || frontmatter.thumbnail;
    if (featuredImagePath) {
      let featuredPath = path.resolve(path.dirname(filePath), featuredImagePath);
      
      // Fallback: Try resolving from project root
      try {
        await fs.access(featuredPath);
      } catch {
        const rootPath = path.resolve(process.cwd(), featuredImagePath);
        try {
          await fs.access(rootPath);
          featuredPath = rootPath;
        } catch {
          // Keep original path
        }
      }

      const media = await this.imageProcessor.processAndUpload(featuredPath, 'Featured Image');
      if (media) featuredMediaId = media.id;
    } else if (firstUploadedImageId) {
      featuredMediaId = firstUploadedImageId;
    }

    // 5. Handle Parent Post
    let parentId = 0;
    if (frontmatter.parent) {
      const parentPost = await this.wp.getPostBySlug(frontmatter.parent);
      if (parentPost) parentId = parentPost.id;
    }

    // 3.5. SEO: normalize slug
    let finalSlug = frontmatter.slug;
    const rawTitle = (frontmatter.title || '').toString();
    if (!finalSlug && rawTitle) {
      const normalized = slugify(rawTitle);
      const hasNonAscii = /[^\x00-\x7F]/.test(rawTitle);
      if (normalized && (!hasNonAscii ? normalized.length >= 3 : normalized.length >= 6)) {
        finalSlug = normalized;
      } else {
        finalSlug = rawTitle.toLowerCase().trim().replace(/\s+/g, '-');
      }
    } else if (finalSlug) {
      const normalized = slugify(finalSlug);
      const hasNonAscii = /[^\x00-\x7F]/.test(finalSlug);
      if (normalized && (!hasNonAscii ? normalized.length >= 3 : normalized.length >= 6)) {
        finalSlug = normalized;
      } else {
        finalSlug = String(finalSlug).toLowerCase().trim().replace(/\s+/g, '-');
      }
    }

    return {
      title: frontmatter.title,
      content: htmlContent,
      status: frontmatter.status || 'publish',
      slug: finalSlug || frontmatter.slug,
      parent: parentId,
      date: frontmatter.date,
      categories: categoryIds,
      tags: tagIds,
      featured_media: featuredMediaId,
      excerpt: excerpt,
      id: frontmatter.id
    };
  }

  async post(filePath) {
    const postData = await this.processFile(filePath);
    
    let existingPost = null;
    if (postData.id) {
      existingPost = { id: postData.id };
    } else {
      existingPost = await this.wp.getPostBySlug(postData.slug);
    }

    let result;

    if (existingPost) {
      console.log(`Updating existing post: ${postData.slug} (ID: ${existingPost.id})`);
      result = await this.wp.updatePost(existingPost.id, postData);
    } else {
      console.log(`Creating new post: ${postData.slug}`);
      // Create draft first strategy
      const draftData = { ...postData, content: 'Temp content...', status: 'draft' };
      const draft = await this.wp.createPost(draftData);
      console.log(`Draft created (ID: ${draft.id}). Uploading full content...`);
      result = await this.wp.updatePost(draft.id, { 
        content: postData.content, 
        status: postData.status 
      });
    }

    return result;
  }

  resolveCategories(names, metadataList) {
    if (!names || !Array.isArray(names)) return [];
    const ids = [];
    for (const name of names) {
      const nameStr = String(name);
      const found = metadataList.find(m => 
        String(m.name).toLowerCase() === nameStr.toLowerCase() || 
        String(m.slug).toLowerCase() === nameStr.toLowerCase() ||
        decodeURIComponent(m.slug).toLowerCase() === nameStr.toLowerCase()
      );
      if (!found) {
        throw new Error(`Category '${name}' does not exist. Creating new categories is prohibited. Please use 'node src/index.js list categories' to see available options.`);
      }
      ids.push(found.id);
    }
    return ids;
  }

  async resolveTags(names, metadataList) {
    if (!names || !Array.isArray(names)) return [];
    const ids = [];
    for (const name of names) {
      const nameStr = String(name);
      const found = metadataList.find(m => String(m.name).toLowerCase() === nameStr.toLowerCase() || String(m.slug).toLowerCase() === nameStr.toLowerCase());
      if (found) {
        ids.push(found.id);
      } else {
        console.log(`Tag '${name}' not found. Creating...`);
        const newTag = await this.wp.createTag(nameStr);
        ids.push(newTag.id);
      }
    }
    return ids;
  }

  async schedulePost(filePath) {
    console.log('Finding next available schedule slot...');
    
    // 1. Get future posts
    const futurePosts = await this.wp.getFuturePosts();
    // Create a set of occupied dates (YYYY-MM-DD)
    const occupiedDates = new Set(futurePosts.map(p => {
      return new Date(p.date).toISOString().split('T')[0];
    }));

    // 2. Find next available slot (starting tomorrow)
    let targetDate = new Date();
    targetDate.setDate(targetDate.getDate() + 1); // Start from tomorrow
    
    // Loop until we find a date that is NOT in occupiedDates
    // Limit to 365 days to prevent infinite loop
    for (let i = 0; i < 365; i++) {
      const dateStr = targetDate.toISOString().split('T')[0];
      if (!occupiedDates.has(dateStr)) {
        break;
      }
      targetDate.setDate(targetDate.getDate() + 1);
    }

    // Set time to 09:00:00 (Morning)
    targetDate.setHours(9, 0, 0, 0);
    
    // Format: YYYY-MM-DD HH:mm:ss
    const year = targetDate.getFullYear();
    const month = String(targetDate.getMonth() + 1).padStart(2, '0');
    const day = String(targetDate.getDate()).padStart(2, '0');
    const hours = String(targetDate.getHours()).padStart(2, '0');
    const minutes = String(targetDate.getMinutes()).padStart(2, '0');
    const seconds = String(targetDate.getSeconds()).padStart(2, '0');
    
    const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

    console.log(`Scheduled for: ${formattedDate}`);

    // 3. Update file frontmatter
    let fileContent = await fs.readFile(filePath, 'utf-8');
    
    // Update date
    if (fileContent.match(/^date:.*$/m)) {
      fileContent = fileContent.replace(/^date:.*$/m, `date: ${formattedDate}`);
    } else {
      // Insert after title if date missing
      fileContent = fileContent.replace(/^title:.*$/m, `$& \ndate: ${formattedDate}`);
    }
    
    // Update status to future
    if (fileContent.match(/^status:.*$/m)) {
      fileContent = fileContent.replace(/^status:.*$/m, `status: future`);
    } else {
      fileContent = fileContent.replace(/^---/, `---\nstatus: future`);
    }

    await fs.writeFile(filePath, fileContent);
    
    // 4. Post
    return await this.post(filePath);
  }
}
