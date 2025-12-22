import axios from 'axios';
import { config } from '../config/index.js';

export class WpClient {
  constructor() {
    if (!config.wp.baseUrl || !config.wp.username || !config.wp.password) {
      throw new Error('Missing WordPress credentials in .env');
    }

    const cleanPassword = config.wp.password.replace(/\s/g, '');

    this.authHeader = {
      Authorization: 'Basic ' + Buffer.from(`${config.wp.username}:${cleanPassword}`).toString('base64'),
      'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
      'Accept': 'application/json, */*',
      'X-Requested-With': 'XMLHttpRequest',
      'Referer': 'https://www.zidooka.com/',
      'Origin': 'https://www.zidooka.com'
    };
    
    this.baseUrl = config.wp.baseUrl;
    this.mediaBaseUrl = config.wp.mediaBaseUrl;
  }

  async checkAuth() {
    try {
      const res = await axios.get(`${this.baseUrl}/wp/v2/users/me`, { headers: this.authHeader });
      return { success: true, user: res.data };
    } catch (error) {
      return { success: false, error: error.response ? error.response.data : error.message };
    }
  }

  async getCategories() {
    const res = await axios.get(`${this.baseUrl}/wp/v2/categories?per_page=100`, { headers: this.authHeader });
    return res.data;
  }

  async getTags() {
    const res = await axios.get(`${this.baseUrl}/wp/v2/tags?per_page=100`, { headers: this.authHeader });
    return res.data;
  }

  async getPostBySlug(slug) {
    try {
      const res = await axios.get(`${this.baseUrl}/wp/v2/posts?slug=${slug}&status=any`, { headers: this.authHeader });
      return res.data.length > 0 ? res.data[0] : null;
    } catch (error) {
      return null;
    }
  }

  async createPost(data) {
    const res = await axios.post(`${this.baseUrl}/wp/v2/posts`, data, { headers: this.authHeader });
    return res.data;
  }

  async updatePost(id, data) {
    const res = await axios.post(`${this.baseUrl}/wp/v2/posts/${id}`, data, { headers: this.authHeader });
    return res.data;
  }

  async uploadMedia(fileBuffer, filename, mimeType) {
    const res = await axios.post(`${this.mediaBaseUrl}/wp/v2/media`, fileBuffer, {
      headers: {
        ...this.authHeader,
        'Content-Disposition': `attachment; filename="${filename}"`,
        'Content-Type': mimeType
      }
    });
    return res.data;
  }

  async updateMediaMetadata(id, { alt_text, caption }) {
    await axios.post(`${this.baseUrl}/wp/v2/media/${id}`, {
      alt_text,
      caption
    }, { headers: this.authHeader });
  }

  async getPosts(page = 1, perPage = 100) {
    const res = await axios.get(`${this.baseUrl}/wp/v2/posts?page=${page}&per_page=${perPage}`, { headers: this.authHeader });
    return {
      data: res.data,
      totalPages: parseInt(res.headers['x-wp-totalpages'], 10)
    };
  }

  async createTag(name) {
    try {
      const res = await axios.post(`${this.baseUrl}/wp/v2/tags`, { name }, { headers: this.authHeader });
      return res.data;
    } catch (error) {
      if (error.response && error.response.data.code === 'term_exists') {
        const existingId = error.response.data.data.term_id;
        return { id: existingId, name };
      }
      throw error;
    }
  }

  async createCategory(name, slug = null, parent = 0) {
    try {
      const data = { name };
      if (slug) data.slug = slug;
      if (parent) data.parent = parent;
      
      const res = await axios.post(`${this.baseUrl}/wp/v2/categories`, data, { headers: this.authHeader });
      return res.data;
    } catch (error) {
      if (error.response && error.response.data.code === 'term_exists') {
        const existingId = error.response.data.data.term_id;
        return { id: existingId, name };
      }
      throw error;
    }
  }

  async updateCategory(id, data) {
    const res = await axios.post(`${this.baseUrl}/wp/v2/categories/${id}`, data, { headers: this.authHeader });
    return res.data;
  }

  async deleteCategory(id) {
    const res = await axios.delete(`${this.baseUrl}/wp/v2/categories/${id}?force=true`, { headers: this.authHeader });
    return res.data;
  }

  async getFuturePosts() {
    try {
      const res = await axios.get(`${this.baseUrl}/wp/v2/posts?status=future&per_page=100`, { headers: this.authHeader });
      return res.data;
    } catch (error) {
      console.error('Failed to fetch future posts:', error.message);
      return [];
    }
  }
}
