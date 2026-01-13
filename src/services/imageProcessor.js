import fs from 'fs/promises';
import path from 'path';
import sharp from 'sharp';
import { slugify } from '../utils/seo.js';
import { WpClient } from './wpClient.js';

export class ImageProcessor {
  constructor(wpClient) {
    this.wp = wpClient;
  }

  async processAndUpload(localPath, altText) {
    try {
      await fs.access(localPath);
      
      let filename = path.basename(localPath);
      let fileBuffer = await fs.readFile(localPath);
      let mimeType = this.getMimeType(filename);

      // Resize and convert to JPEG for large raster images (skip svg/gif/webp)
      if (mimeType.startsWith('image/')) {
        try {
          const image = sharp(fileBuffer);
          const metadata = await image.metadata();
          const fmt = (metadata.format || '').toLowerCase();

          if (fmt && !['svg', 'gif', 'webp'].includes(fmt)) {
            console.log(`Resizing and converting to JPEG (Original: ${metadata.width || '?'}px, ${fmt})...`);
            fileBuffer = await image
              .resize({ width: 1600, withoutEnlargement: true })
              .jpeg({ quality: 80 })
              .toBuffer();
        const nameWithoutExt = path.parse(filename).name;
        const base = slugify(nameWithoutExt) || nameWithoutExt.toLowerCase().replace(/\s+/g, '-');
        filename = `${base}.jpg`;
        mimeType = 'image/jpeg';
      }
        } catch {
          // If sharp can't parse (e.g., SVG), leave as-is
        }
      }

      // Normalize non-converted filenames too
      if (!filename.toLowerCase().endsWith('.jpg')) {
        const parsed = path.parse(filename);
        const base = slugify(parsed.name) || parsed.name.toLowerCase().replace(/\s+/g, '-');
        filename = `${base}${parsed.ext}`;
      }

      console.log(`Uploading ${filename}...`);
      const media = await this.wp.uploadMedia(fileBuffer, filename, mimeType);

      if (altText) {
        await this.wp.updateMediaMetadata(media.id, { alt_text: altText });
      }

      return media;
    } catch (e) {
      console.warn(`[Warning] Image processing failed for ${localPath}: ${e.message}`);
      return null;
    }
  }

  getMimeType(filename) {
    const ext = path.extname(filename).toLowerCase();
    switch (ext) {
      case '.png': return 'image/png';
      case '.gif': return 'image/gif';
      case '.svg': return 'image/svg+xml';
      case '.webp': return 'image/webp';
      case '.jpg':
      case '.jpeg': return 'image/jpeg';
      default: return 'application/octet-stream';
    }
  }
}
