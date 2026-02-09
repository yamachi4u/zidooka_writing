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

  resolvePostType(frontmatter) {
    const raw = frontmatter?.post_type ?? frontmatter?.postType ?? frontmatter?.type ?? 'post';
    const t = String(raw || '').trim();
    if (!t) return 'posts';

    const lower = t.toLowerCase();
    if (lower === 'post' || lower === 'posts') return 'posts';
    if (lower === 'page' || lower === 'pages') return 'pages';
    return lower; // Custom post type REST base (e.g. "gas_script")
  }

  normalizeNewlines(text) {
    return String(text || '').replace(/\r\n/g, '\n').replace(/\r/g, '\n');
  }

  async resolveTextPath(filePath, inputPath) {
    let decoded = String(inputPath || '');
    try {
      decoded = decodeURIComponent(decoded);
    } catch {
      // keep as-is
    }

    let absolute = path.resolve(path.dirname(filePath), decoded);
    try {
      await fs.access(absolute);
      return absolute;
    } catch {
      const rootPath = path.resolve(process.cwd(), decoded);
      try {
        await fs.access(rootPath);
        return rootPath;
      } catch {
        return absolute;
      }
    }
  }

  async resolveGasFiles(files, filePath) {
    const out = [];
    for (const item of files) {
      let fileRel = '';
      let name = '';

      if (typeof item === 'string') {
        fileRel = item;
        name = path.basename(fileRel);
      } else if (item && typeof item === 'object') {
        fileRel = item.path || item.file || item.src || item.source || '';
        name = item.name || item.filename || item.target || '';
        if (!name && fileRel) name = path.basename(String(fileRel));
      }

      if (!fileRel) continue;

      const abs = await this.resolveTextPath(filePath, fileRel);
      const content = await fs.readFile(abs, 'utf-8');
      out.push({ name: String(name || 'Code.gs'), content: this.normalizeNewlines(content) });
    }
    return out;
  }

  buildBundleText(files) {
    let out = '';
    for (const f of files) {
      const name = String(f?.name || 'Code.gs').trim() || 'Code.gs';
      const content = this.normalizeNewlines(f?.content || '');
      out += `--- file: ${name} ---\n${content}\n\n`;
    }
    return out.trimEnd();
  }

  async buildGasMeta(frontmatter, filePath) {
    const gas = (frontmatter?.gas && typeof frontmatter.gas === 'object') ? frontmatter.gas : {};

    const version = gas.version ?? frontmatter?.gas_version ?? frontmatter?.gasVersion ?? '';
    let filename = gas.filename ?? gas.download_filename ?? frontmatter?.gas_filename ?? frontmatter?.gasFilename ?? '';

    const modeRaw = gas.mode ?? frontmatter?.gas_mode ?? frontmatter?.gasMode ?? '';
    const mode = String(modeRaw || '').trim().toLowerCase(); // "single" | "bundle" | ""

    const bundleInline = gas.bundle ?? frontmatter?.gas_bundle ?? frontmatter?.gasBundle ?? '';
    const codeInline = gas.code ?? frontmatter?.gas_code ?? frontmatter?.gasCode ?? '';
    const files = gas.files ?? frontmatter?.gas_files ?? frontmatter?.gasFiles ?? null;
    const codeFile = gas.code_file ?? gas.codeFile ?? frontmatter?.gas_code_file ?? frontmatter?.gasCodeFile ?? '';

    let bundleText = '';
    let codeText = '';

    if (bundleInline) {
      bundleText = this.normalizeNewlines(bundleInline);
    } else if (Array.isArray(files) && files.length > 0) {
      const resolved = await this.resolveGasFiles(files, filePath);
      if (resolved.length === 1 && mode !== 'bundle') {
        codeText = resolved[0].content;
        if (!filename) filename = resolved[0].name;
      } else {
        bundleText = this.buildBundleText(resolved);
      }
    } else if (codeInline) {
      codeText = this.normalizeNewlines(codeInline);
    } else if (codeFile) {
      const abs = await this.resolveTextPath(filePath, codeFile);
      codeText = this.normalizeNewlines(await fs.readFile(abs, 'utf-8'));
      if (!filename) filename = path.basename(String(codeFile));
    }

    const meta = {};
    if (version) meta['_zdk_gas_version'] = String(version);
    if (filename) meta['_zdk_gas_filename'] = String(filename);

    if (bundleText && mode !== 'single') {
      meta['_zdk_gas_bundle'] = bundleText;
      meta['_zdk_gas_code'] = '';
    } else if (codeText) {
      meta['_zdk_gas_code'] = codeText;
      meta['_zdk_gas_bundle'] = '';
    }

    return meta;
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
    const postType = this.resolvePostType(frontmatter);


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

    const data = {
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

    if (postType === 'gas_script') {
      const meta = await this.buildGasMeta(frontmatter, filePath);
      const hasBundle = typeof meta?._zdk_gas_bundle === 'string' && meta._zdk_gas_bundle.trim() !== '';
      const hasCode = typeof meta?._zdk_gas_code === 'string' && meta._zdk_gas_code.trim() !== '';
      if (!hasBundle && !hasCode) {
        throw new Error('gas_script requires GAS distribution data. Provide `gas.code`, `gas.code_file`, `gas.bundle`, or `gas.files` in frontmatter.');
      }
      data.meta = meta;
    }

    return { type: postType, data };
  }

  async post(filePath) {
    const { type: postType, data: postData } = await this.processFile(filePath);
    
    let existingPost = null;
    if (postData.id) {
      existingPost = { id: postData.id };
    } else {
      existingPost = await this.wp.getPostBySlug(postData.slug, postType);
    }

    let result;

    if (existingPost) {
      console.log(`Updating existing post: ${postData.slug} (ID: ${existingPost.id})`);
      const { id: _ignore, ...payload } = postData;
      result = await this.wp.updatePost(existingPost.id, payload, postType);
    } else {
      console.log(`Creating new post: ${postData.slug}`);
      // Create draft first strategy
      const { id: _ignore, meta: _metaIgnore, ...draftPayload } = postData;
      const draftData = { ...draftPayload, content: 'Temp content...', status: 'draft' };
      const draft = await this.wp.createPost(draftData, postType);
      console.log(`Draft created (ID: ${draft.id}). Uploading full content...`);
      const { id: _ignore2, ...payload } = postData;
      result = await this.wp.updatePost(draft.id, payload, postType);
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
