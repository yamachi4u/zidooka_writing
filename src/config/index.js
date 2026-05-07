import dotenv from 'dotenv';
import path from 'path';

dotenv.config();

export const config = {
  wp: {
    baseUrl: process.env.WP_API_URL,
    mediaBaseUrl: process.env.WP_MEDIA_API_URL || process.env.WP_API_URL,
    username: process.env.WP_USER,
    password: process.env.WP_APP_PASSWORD,
    timezone: process.env.WP_TIMEZONE || process.env.SITE_TIMEZONE || 'Asia/Tokyo',
  },
  paths: {
    metadata: path.join(process.cwd(), 'data', 'metadata.json'),
    drafts: path.join(process.cwd(), 'drafts'),
    images: path.join(process.cwd(), 'images'),
  }
};
