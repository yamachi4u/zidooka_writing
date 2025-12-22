import { marked } from 'marked';

// Configure marked extensions for ZIDOOKA specific syntax
marked.use({
  extensions: [{
    name: 'zdkStrong',
    level: 'inline',
    start(src) { return src.match(/==/)?.index; },
    tokenizer(src, tokens) {
      const rule = /^==(.*?)==/;
      const match = rule.exec(src);
      if (match) {
        return {
          type: 'zdkStrong',
          raw: match[0],
          text: match[1].trim(),
          tokens: this.lexer.inlineTokens(match[1].trim())
        };
      }
    },
    renderer(token) {
      return `<span class="zdk_i_strong">${this.parser.parseInline(token.tokens)}</span>`;
    }
  }],
  renderer: {
    codespan(text) {
      return `<span class="zdk_i_code">${text}</span>`;
    }
  }
});

export class MarkdownConverter {
  constructor(imageMap = {}) {
    this.imageMap = imageMap;
    this.allowedBlockTypes = ['note', 'warning', 'step', 'example', 'conclusion'];
  }

  convertToBlocks(markdown) {
    // 1. Split by custom blocks (:::type ... :::)
    const blockRegex = /^:::(\w+)\s*([\s\S]*?)^:::/gm;
    const segments = [];
    let lastIndex = 0;
    let match;

    while ((match = blockRegex.exec(markdown)) !== null) {
      // Text before the block
      if (match.index > lastIndex) {
        segments.push({ type: 'markdown', content: markdown.slice(lastIndex, match.index) });
      }

      // The block itself
      const blockType = match[1];
      const blockContent = match[2];
      segments.push({ type: 'custom', blockType, content: blockContent });

      lastIndex = blockRegex.lastIndex;
    }

    // Remaining text
    if (lastIndex < markdown.length) {
      segments.push({ type: 'markdown', content: markdown.slice(lastIndex) });
    }

    // 2. Process segments
    let html = '';
    for (const segment of segments) {
      if (segment.type === 'custom') {
        html += this.renderCustomBlock(segment.blockType, segment.content);
      } else {
        html += this.processMarkdownSegment(segment.content);
      }
    }
    return html;
  }

  processMarkdownSegment(markdown) {
    const tokens = marked.lexer(markdown);
    let html = '';
    for (const token of tokens) {
      html += this.processToken(token);
    }
    return html;
  }

  renderCustomBlock(type, content) {
    if (!this.allowedBlockTypes.includes(type)) {
      // Fallback to paragraph if unknown type, but treat content as markdown
      return this.processMarkdownSegment(content);
    }

    const className = `zdk_b_${type}`;
    // Parse inner content as blocks (paragraphs, lists, etc.)
    // Ensure inner content is standard Gutenberg blocks
    const innerBlocks = this.processMarkdownSegment(content.trim());
    
    return `<!-- wp:group {"className":"${className}"} -->\n<div class="wp-block-group ${className}">\n${innerBlocks}</div>\n<!-- /wp:group -->\n\n`;
  }

  processToken(token) {
    switch (token.type) {
      case 'heading':
        return this.renderHeading(token);
      case 'paragraph':
        return this.renderParagraph(token);
      case 'list':
        return this.renderList(token);
      case 'blockquote':
        return this.renderBlockquote(token);
      case 'code':
        return this.renderCode(token);
      case 'hr':
        return `<!-- wp:separator -->\n<hr class="wp-block-separator has-alpha-channel-opacity"/>\n<!-- /wp:separator -->\n\n`;
      case 'space':
        return '';
      case 'html':
        // Prevent raw HTML blocks, convert to paragraph with escaped HTML
        return `<!-- wp:paragraph -->\n<p>${this.escapeHtml(token.text)}</p>\n<!-- /wp:paragraph -->\n\n`;
      default:
        // Fallback for anything else
        if (token.text) {
             return `<!-- wp:paragraph -->\n<p>${marked.parseInline(token.text)}</p>\n<!-- /wp:paragraph -->\n\n`;
        }
        return '';
    }
  }

  renderHeading(token) {
    const level = token.depth;
    const text = marked.parseInline(token.text);
    const anchor = token.text.toLowerCase()
      .replace(/[^\w\s-]/g, '')
      .replace(/\s+/g, '-');
    
    return `<!-- wp:heading {"level":${level}} -->\n<h${level} class="wp-block-heading" id="${anchor}">${text}</h${level}>\n<!-- /wp:heading -->\n\n`;
  }

  renderParagraph(token) {
    if (token.tokens && token.tokens.length === 1 && token.tokens[0].type === 'image') {
      return this.renderImage(token.tokens[0]);
    }
    const text = marked.parseInline(token.text);
    return `<!-- wp:paragraph -->\n<p>${text}</p>\n<!-- /wp:paragraph -->\n\n`;
  }

  renderImage(imgToken) {
    const url = imgToken.href;
    const alt = imgToken.text;
    const id = this.imageMap[url];
    
    if (id) {
      return `<!-- wp:image {"id":${id},"sizeSlug":"large","linkDestination":"none"} -->\n<figure class="wp-block-image size-large"><img src="${url}" alt="${alt}" class="wp-image-${id}"/></figure>\n<!-- /wp:image -->\n\n`;
    }
    return `<!-- wp:image {"linkDestination":"none"} -->\n<figure class="wp-block-image"><img src="${url}" alt="${alt}"/></figure>\n<!-- /wp:image -->\n\n`;
  }

  renderList(token) {
    const tagName = token.ordered ? 'ol' : 'ul';
    const attrs = token.ordered ? '{"ordered":true}' : '';
    const cleanAttrs = attrs ? ` ${attrs}` : '';
    // marked.parser uses the renderer which we customized for codespan and zdkStrong
    const listHtml = marked.parser([token]).trim();
    return `<!-- wp:list${cleanAttrs} -->\n${listHtml}\n<!-- /wp:list -->\n\n`;
  }

  renderBlockquote(token) {
    let quoteContent = '';
    if (token.tokens) {
      for (const t of token.tokens) {
        if (t.type === 'paragraph') {
          quoteContent += `<p>${marked.parseInline(t.text)}</p>`;
        } else {
          quoteContent += marked.parser([t]);
        }
      }
    } else {
      quoteContent = `<p>${marked.parseInline(token.text)}</p>`;
    }
    return `<!-- wp:quote -->\n<blockquote class="wp-block-quote">\n${quoteContent}\n</blockquote>\n<!-- /wp:quote -->\n\n`;
  }

  renderCode(token) {
    const code = token.text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
    
    const language = token.lang || 'plaintext';
    const codeClass = `class="language-${language}"`;
    
    return `<!-- wp:code -->\n<pre class="wp-block-code"><code ${codeClass}>${code}</code></pre>\n<!-- /wp:code -->\n\n`;
  }

  escapeHtml(text) {
    return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }
}
