import fs from "node:fs/promises";
import path from "node:path";
import matter from "gray-matter";
import { marked } from "marked";

const inputArg = process.argv[2];

if (!inputArg) {
  console.error("Usage: node scripts/render-article-preview.mjs <markdown-file>");
  process.exit(1);
}

const rootDir = process.cwd();
const inputPath = path.resolve(rootDir, inputArg);
const previewsDir = path.join(rootDir, "tmp", "article-preview");
const htmlPath = path.join(previewsDir, `${path.basename(inputPath, path.extname(inputPath))}.html`);

const blockLabel = {
  conclusion: "結論",
  note: "ポイント",
  warning: "注意",
  step: "対処",
  example: "具体例",
};

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

function convertCallouts(markdown) {
  return markdown.replace(
    /^:::(conclusion|note|warning|step|example)\n([\s\S]*?)\n:::/gm,
    (_, kind, body) => {
      const label = blockLabel[kind];
      const html = marked.parse(body.trim());
      return `<section class="callout ${kind}"><div class="callout-label">${label}</div>${html}</section>`;
    }
  );
}

function absolutizeImagePaths(html, markdownDir) {
  return html.replace(/src="([^"]+)"/g, (_, src) => {
    if (/^(https?:|file:|data:)/i.test(src)) {
      return `src="${src}"`;
    }
    const abs = path.resolve(markdownDir, src).replace(/\\/g, "/");
    return `src="file:///${abs}"`;
  });
}

const raw = await fs.readFile(inputPath, "utf8");
const parsed = matter(raw);
const normalizedMarkdown = convertCallouts(parsed.content);
const bodyHtml = marked.parse(normalizedMarkdown);
const articleHtml = absolutizeImagePaths(bodyHtml, path.dirname(inputPath));
const title = parsed.data.title || path.basename(inputPath);

const html = `<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>${escapeHtml(title)}</title>
  <style>
    :root {
      --bg: #f4f0e8;
      --paper: #fffdfc;
      --text: #1f2937;
      --muted: #6b7280;
      --line: #d6d3d1;
      --accent: #0e7490;
      --accent2: #e76f51;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      background: linear-gradient(180deg, #efe8dd 0%, #f8f4ee 100%);
      color: var(--text);
      font-family: "Segoe UI", "Yu Gothic UI", sans-serif;
      line-height: 1.8;
    }
    main {
      max-width: 920px;
      margin: 40px auto 80px;
      background: var(--paper);
      border: 1px solid var(--line);
      border-radius: 24px;
      padding: 56px 64px 64px;
      box-shadow: 0 20px 60px rgba(31, 41, 55, 0.08);
    }
    h1, h2, h3 { line-height: 1.3; }
    h1 { font-size: 2.4rem; margin: 0 0 1rem; }
    h2 {
      margin-top: 2.8rem;
      padding-top: 1.2rem;
      border-top: 1px solid var(--line);
      font-size: 1.6rem;
    }
    p, li { font-size: 1.05rem; }
    img {
      width: 100%;
      height: auto;
      margin: 1rem 0 1.2rem;
      border: 1px solid var(--line);
      border-radius: 18px;
      background: #fff;
    }
    code {
      padding: 0.15rem 0.4rem;
      background: #f5f7fa;
      border-radius: 6px;
      font-family: "Cascadia Code", Consolas, monospace;
      font-size: 0.95em;
    }
    pre {
      padding: 18px 20px;
      overflow: auto;
      background: #0f172a;
      color: #f8fafc;
      border-radius: 16px;
    }
    pre code {
      padding: 0;
      background: transparent;
      color: inherit;
    }
    .callout {
      margin: 1.4rem 0;
      padding: 1rem 1.1rem;
      border-radius: 16px;
      border: 1px solid var(--line);
      background: #fbfaf8;
    }
    .callout-label {
      display: inline-block;
      margin-bottom: 0.4rem;
      padding: 0.15rem 0.6rem;
      border-radius: 999px;
      font-size: 0.88rem;
      font-weight: 700;
      letter-spacing: 0.02em;
      color: #fff;
      background: var(--accent);
    }
    .warning .callout-label { background: #b45309; }
    .example .callout-label { background: #7c3aed; }
    .step .callout-label { background: #0f766e; }
    .conclusion .callout-label { background: var(--accent2); }
    .meta {
      margin-bottom: 1.4rem;
      color: var(--muted);
      font-size: 0.95rem;
    }
  </style>
</head>
<body>
  <main>
    <div class="meta">${escapeHtml(path.relative(rootDir, inputPath))}</div>
    ${articleHtml}
  </main>
</body>
</html>`;

await fs.mkdir(previewsDir, { recursive: true });
await fs.writeFile(htmlPath, html, "utf8");
console.log(htmlPath);
