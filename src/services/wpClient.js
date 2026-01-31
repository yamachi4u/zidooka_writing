import axios from 'axios';
import FormData from 'form-data';
import { config } from '../config/index.js';

export class WpClient {
  constructor() {
    if (!config.wp.baseUrl || !config.wp.username || !config.wp.password) {
      throw new Error('Missing WordPress credentials in .env');
    }

    const cleanPassword = config.wp.password.replace(/\s/g, '');
    // Initialize base URLs first
    this.baseUrl = config.wp.baseUrl;
    this.mediaBaseUrl = config.wp.mediaBaseUrl;
    this.timeout = Number(process.env.HTTP_TIMEOUT_MS) || 20000;

    // Derive site origin from configured base URL (handles e.g. .../wp-json)
    let origin;
    try {
      origin = new URL(this.baseUrl).origin;
    } catch {
      // Fallback: strip trailing path heuristically
      origin = (this.baseUrl || '').replace(/\/(wp-json.*)?$/, '');
    }

    this.authHeader = {
      Authorization: 'Basic ' + Buffer.from(`${config.wp.username}:${cleanPassword}`).toString('base64'),
      'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
      'Accept': 'application/json, */*',
      'X-Requested-With': 'XMLHttpRequest',
      'Referer': origin + '/',
      'Origin': origin
    };
  }

  async checkAuth() {
    try {
      const res = await this._requestWithRetry(() => axios.get(`${this.baseUrl}/wp/v2/users/me`, { headers: this.authHeader, timeout: this.timeout }), 'GET users/me');
      return { success: true, user: res.data };
    } catch (error) {
      return { success: false, error: error.response ? error.response.data : error.message };
    }
  }

  async getCategories() {
    return await this._fetchAllPages(`${this.baseUrl}/wp/v2/categories`, 100);
  }

  async getTags() {
    return await this._fetchAllPages(`${this.baseUrl}/wp/v2/tags`, 100);
  }

  async getPostBySlug(slug) {
    try {
      const res = await this._requestWithRetry(() => axios.get(`${this.baseUrl}/wp/v2/posts?slug=${slug}&status=any`, { headers: this.authHeader, timeout: this.timeout }), 'GET post by slug');
      return res.data.length > 0 ? res.data[0] : null;
    } catch (error) {
      return null;
    }
  }

  async createPost(data) {
    const res = await this._requestWithRetry(() => axios.post(`${this.baseUrl}/wp/v2/posts`, data, { headers: this.authHeader, timeout: this.timeout }), 'POST create post');
    return res.data;
  }

  async updatePost(id, data) {
    const res = await this._requestWithRetry(() => axios.post(`${this.baseUrl}/wp/v2/posts/${id}`, data, { headers: this.authHeader, timeout: this.timeout }), 'POST update post');
    return res.data;
  }

  async uploadMedia(fileBuffer, filename, mimeType) {
    // Use FormData (multipart/form-data) to avoid WAF blocking raw binary uploads
    const formData = new FormData();
    formData.append('file', fileBuffer, {
      filename: filename,
      contentType: mimeType
    });

    const res = await this._requestWithRetry(() => axios.post(`${this.mediaBaseUrl}/wp/v2/media`, formData, {
      headers: {
        ...this.authHeader,
        ...formData.getHeaders()
      },
      timeout: this.timeout
    }), 'POST upload media');
    return res.data;
  }

  async updateMediaMetadata(id, { alt_text, caption }) {
    await this._requestWithRetry(() => axios.post(`${this.baseUrl}/wp/v2/media/${id}`, {
      alt_text,
      caption
    }, { headers: this.authHeader, timeout: this.timeout }), 'POST update media');
  }

  async getPosts(page = 1, perPage = 100) {
    const res = await this._requestWithRetry(() => axios.get(`${this.baseUrl}/wp/v2/posts?page=${page}&per_page=${perPage}`, { headers: this.authHeader, timeout: this.timeout }), 'GET posts paged');
    return {
      data: res.data,
      totalPages: parseInt(res.headers['x-wp-totalpages'], 10)
    };
  }

  async createTag(name) {
    try {
      const res = await this._requestWithRetry(() => axios.post(`${this.baseUrl}/wp/v2/tags`, { name }, { headers: this.authHeader, timeout: this.timeout }), 'POST create tag');
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
      
      const res = await this._requestWithRetry(() => axios.post(`${this.baseUrl}/wp/v2/categories`, data, { headers: this.authHeader, timeout: this.timeout }), 'POST create category');
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
    const res = await this._requestWithRetry(() => axios.post(`${this.baseUrl}/wp/v2/categories/${id}`, data, { headers: this.authHeader, timeout: this.timeout }), 'POST update category');
    return res.data;
  }

  async deleteCategory(id) {
    const res = await this._requestWithRetry(() => axios.delete(`${this.baseUrl}/wp/v2/categories/${id}?force=true`, { headers: this.authHeader, timeout: this.timeout }), 'DELETE category');
    return res.data;
  }

  async getFuturePosts() {
    try {
      const all = await this._fetchAllPages(`${this.baseUrl}/wp/v2/posts?status=future`, 100);
      return all;
    } catch (error) {
      console.error('Failed to fetch future posts:', error.message);
      return [];
    }
  }

  async _fetchAllPages(base, perPage = 100) {
    const all = [];
    let page = 1;
    while (true) {
      const sep = base.includes('?') ? '&' : '?';
      const res = await this._requestWithRetry(() => axios.get(`${base}${sep}page=${page}&per_page=${perPage}`, { headers: this.authHeader, timeout: this.timeout }), 'GET paged');
      all.push(...res.data);
      const totalPages = parseInt(res.headers['x-wp-totalpages'] || '1', 10);
      if (!totalPages || page >= totalPages) break;
      page += 1;
    }
    return all;
  }

  async _requestWithRetry(fn, label = 'request') {
    const maxRetries = Number(process.env.HTTP_RETRIES) || 3;
    const baseDelay = Number(process.env.HTTP_RETRY_DELAY_MS) || 500;
    let attempt = 0;
    while (true) {
      try {
        return await fn();
      } catch (error) {
        attempt++;
        const status = error?.response?.status;
        const code = error?.code;
        const transient = status === 429 || (status >= 500 && status <= 599) || ['ECONNRESET','ECONNABORTED','ETIMEDOUT','EAI_AGAIN','ENETDOWN','ENETRESET','ENETUNREACH','EHOSTUNREACH'].includes(code);
        if (!transient || attempt > maxRetries) {
          throw error;
        }
        // Honor Retry-After if present
        let retryAfterMs = 0;
        const ra = error?.response?.headers?.['retry-after'] || error?.response?.headers?.['Retry-After'];
        if (ra) {
          const sec = Number(ra);
          if (!Number.isNaN(sec)) {
            retryAfterMs = Math.max(0, sec * 1000);
          } else {
            const dt = Date.parse(ra);
            if (!Number.isNaN(dt)) {
              retryAfterMs = Math.max(0, dt - Date.now());
            }
          }
        }
        const jitter = Math.floor(Math.random() * 200);
        const backoff = baseDelay * Math.pow(2, attempt - 1) + jitter;
        const delay = Math.max(backoff, retryAfterMs);
        await new Promise(r => setTimeout(r, delay));
      }
    }
  }
}
