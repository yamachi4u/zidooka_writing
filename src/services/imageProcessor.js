import fs from 'fs/promises';
import path from 'path';
import sharp from 'sharp';
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

      // Resize and convert to JPEG if it's an image
      if (mimeType.startsWith('image/')) {
        const image = sharp(fileBuffer);
        const metadata = await image.metadata();

        console.log(`Resizing and converting to JPEG (Original: ${metadata.width}px, ${metadata.format})...`);
        fileBuffer = await image
          .resize({ width: 1600, withoutEnlargement: true })
          .jpeg({ quality: 80 })
          .toBuffer();
        
        const nameWithoutExt = path.parse(filename).name;
        filename = `${nameWithoutExt}.jpg`;
        mimeType = 'image/jpeg';
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
      case '.webp': return 'image/webp';
      case '.jpg':
      case '.jpeg': return 'image/jpeg';
      default: return 'application/octet-stream';
    }
  }
}
