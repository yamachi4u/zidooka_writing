export function stripMarkdown(markdown) {
  if (!markdown) return '';
  let text = markdown;
  // Remove code blocks
  text = text.replace(/```[\s\S]*?```/g, ' ');
  // Remove inline code
  text = text.replace(/`[^`]*`/g, ' ');
  // Remove images ![alt](url)
  text = text.replace(/!\[[^\]]*\]\([^\)]*\)/g, ' ');
  // Remove links [text](url) -> text
  text = text.replace(/\[([^\]]+)\]\([^\)]*\)/g, '$1');
  // Remove emphasis **, __, ==
  text = text.replace(/\*\*([^*]+)\*\*/g, '$1');
  text = text.replace(/__([^_]+)__/g, '$1');
  text = text.replace(/==([^=]+)==/g, '$1');
  // Remove headings
  text = text.replace(/^#{1,6}\s+/gm, '');
  // Remove custom blocks :::
  text = text.replace(/^:::[\s\S]*?^:::/gm, ' ');
  // Remove HTML tags
  text = text.replace(/<[^>]+>/g, ' ');
  // Collapse whitespace
  text = text.replace(/\s+/g, ' ').trim();
  return text;
}

export function makeExcerpt(text, maxLen = 160) {
  const clean = stripMarkdown(text);
  if (clean.length <= maxLen) return clean;
  // Cut without breaking words (basic)
  const slice = clean.slice(0, maxLen + 1);
  const lastSpace = slice.lastIndexOf(' ');
  const trimmed = (lastSpace > 50 ? slice.slice(0, lastSpace) : clean.slice(0, maxLen)).trim();
  return trimmed + '…';
}

export function slugify(input) {
  if (!input) return '';
  const lower = String(input).toLowerCase().trim();
  // Remove diacritics
  const ascii = lower.normalize('NFKD').replace(/[\u0300-\u036f]/g, '');
  // Replace whitespace with hyphen
  let slug = ascii.replace(/\s+/g, '-');
  // Remove invalid chars (keep a-z0-9-)
  slug = slug.replace(/[^a-z0-9\-]/g, '');
  // Collapse multiple hyphens
  slug = slug.replace(/-+/g, '-');
  // Trim hyphens
  slug = slug.replace(/^\-+|\-+$/g, '');
  return slug;
}

