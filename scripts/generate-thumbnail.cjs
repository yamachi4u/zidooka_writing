#!/usr/bin/env node
/**
 * ZIDOOKA Thumbnail Generator v5 — photo background + signature badge.
 *
 * Features:
 *   - Built-in stock photo pool (images/bg-stock/) — random pick with --bg
 *   - Dark overlay with accent color tint for readability
 *   - Gradient title text (white → accent-light)
 *   - ZIDOOKA signature badge (bottom-left)
 *   - "M PLUS 1p" font with fallback stack
 *
 * Usage:
 *   node scripts/generate-thumbnail.cjs --title "タイトル" --output path.png [options]
 */

const sharp = require("sharp");
const path = require("path");
const fs = require("fs");
const os = require("os");

const PROJECT_ROOT = path.resolve(__dirname, "..");
const BG_STOCK_DIR = path.join(PROJECT_ROOT, "images", "bg-stock");

// ── Argument parser ───────────────────────────────────────
function parseArgs(argv) {
  const args = {};
  for (let i = 0; i < argv.length; i++) {
    if (argv[i].startsWith("--")) {
      const key = argv[i].slice(2);
      const next = argv[i + 1];
      if (next && !next.startsWith("--")) {
        args[key] = next;
        i++;
      } else {
        args[key] = true; // flag without value
      }
    }
  }
  return args;
}

// ── Escape XML ────────────────────────────────────────────
function escapeXml(str) {
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&apos;");
}

// ── Color helpers ─────────────────────────────────────────
function hexToRgb(hex) {
  const h = hex.replace("#", "");
  return [parseInt(h.slice(0, 2), 16), parseInt(h.slice(2, 4), 16), parseInt(h.slice(4, 6), 16)];
}
function rgbToHex(r, g, b) {
  return "#" + [r, g, b].map((c) => Math.max(0, Math.min(255, Math.round(c))).toString(16).padStart(2, "0")).join("");
}
function blendHex(a, b, t) {
  const [r1, g1, b1] = hexToRgb(a);
  const [r2, g2, b2] = hexToRgb(b);
  return rgbToHex(r1 + (r2 - r1) * t, g1 + (g2 - g1) * t, b1 + (b2 - b1) * t);
}
function lighten(hex, amt) { return blendHex(hex, "#ffffff", amt); }
function darken(hex, amt) { return blendHex(hex, "#000000", amt); }

// ── Word-wrap ─────────────────────────────────────────────
function wrapText(text, maxW) {
  const chars = [...text];
  const lines = [];
  let cur = "", w = 0;
  for (const ch of chars) {
    const cw = ch.charCodeAt(0) > 0x2e80 ? 2 : 1;
    if (w + cw > maxW && cur.length > 0) {
      lines.push(cur);
      cur = ch; w = cw;
    } else {
      cur += ch; w += cw;
    }
  }
  if (cur) lines.push(cur);
  return lines;
}

// ── Theme derivation ──────────────────────────────────────
function deriveTheme(accent) {
  return {
    bg1: blendHex("#0c0c14", accent, 0.08),
    bg2: blendHex("#10101e", accent, 0.05),
    titleTop: "#ffffff",
    titleBottom: lighten(accent, 0.65),
    barStart: accent,
    barEnd: darken(accent, 0.4),
    pillBg: accent,
    pillText: lighten(accent, 0.2),
    glow: accent,
  };
}

// ── Accent presets ────────────────────────────────────────
const PRESETS = {
  cyan: "#06b6d4", green: "#10b981", purple: "#8b5cf6", amber: "#f59e0b",
  red: "#ef4444", blue: "#3b82f6", pink: "#ec4899", orange: "#f97316",
  teal: "#14b8a6", indigo: "#6366f1",
};

// ── Pick random stock background ──────────────────────────
function pickRandomBg() {
  if (!fs.existsSync(BG_STOCK_DIR)) return null;
  const files = fs.readdirSync(BG_STOCK_DIR).filter((f) =>
    /\.(png|jpe?g|webp)$/i.test(f)
  );
  if (files.length === 0) return null;
  const pick = files[Math.floor(Math.random() * files.length)];
  return path.join(BG_STOCK_DIR, pick);
}

// ══════════════════════════════════════════════════════════
//  FONT & LAYOUT CONSTANTS
// ══════════════════════════════════════════════════════════
const TITLE_FONT = "'M PLUS 1p', 'Hiragino Kaku Gothic ProN', 'Noto Sans JP', 'BIZ UDPGothic', 'Yu Gothic', sans-serif";
const UI_FONT = "'M PLUS 1p', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif";

function calcTitleLayout(title, width, height, hasCat, hasSub) {
  const titleEscaped = escapeXml(title);
  const wu = [...title].reduce((s, ch) => s + (ch.charCodeAt(0) > 0x2e80 ? 2 : 1), 0);
  const maxPxW = width * 0.72;
  const cpf = 0.52;

  let fontSize = Math.floor(maxPxW / (wu * cpf));
  fontSize = Math.min(fontSize, 120);
  fontSize = Math.max(fontSize, 48);

  let cpl = Math.floor(maxPxW / (cpf * fontSize));
  let lines = wrapText(titleEscaped, Math.max(cpl, 10));

  if (lines.length > 3) fontSize = Math.max(42, Math.floor(fontSize * 0.7));
  else if (lines.length > 2) fontSize = Math.max(48, Math.floor(fontSize * 0.85));

  cpl = Math.floor(maxPxW / (cpf * fontSize));
  lines = wrapText(titleEscaped, Math.max(cpl, 10));

  const lineH = fontSize * 1.3;
  const totalTextH = lines.length * lineH;
  const extra = (hasCat ? 58 : 0) + (hasSub ? 65 : 0);
  const contentH = totalTextH + extra + 50;
  const startY = Math.max(60, Math.round((height - contentH) / 2));

  return { lines, fontSize, lineH, startY };
}

// ══════════════════════════════════════════════════════════
//  SVG BUILDERS
// ══════════════════════════════════════════════════════════

/** Signature badge dimensions (consistent across all modes) */
const BADGE = { w: 170, h: 38, x: 50, r: 6 };

function buildSignatureBadgeSvg(accent, width, height) {
  const by = height - 62;
  return `
  <!-- ═══ ZIDOOKA SIGNATURE ═══ -->
  <rect x="${BADGE.x}" y="${by}" width="${BADGE.w}" height="${BADGE.h}" rx="${BADGE.r}" fill="${accent}"/>
  <text x="${BADGE.x + BADGE.w / 2}" y="${by + BADGE.h / 2 + 6}" font-family="${UI_FONT}"
        font-size="16" font-weight="900" fill="white" text-anchor="middle" letter-spacing="4">
    ZIDOOKA
  </text>`;
}

function buildBarsSvg(accent, width, height) {
  return `
  <rect width="${width}" height="5" fill="${accent}"/>
  <rect y="${height - 4}" width="${width}" height="4" fill="${accent}"/>`;
}

function buildTextElements(opts) {
  const { title, subtitle, accent, category, width, height } = opts;
  const theme = deriveTheme(accent);
  const catEscaped = escapeXml(category);
  const subEscaped = escapeXml(subtitle);
  const hasCat = catEscaped.length > 0;
  const hasSub = subEscaped.length > 0;

  const layout = calcTitleLayout(title, width, height, hasCat, hasSub);
  const { lines, fontSize, lineH, startY } = layout;

  const labelY = startY;
  const catY = labelY + 55;
  const titleY = catY + (hasCat ? 55 : 0) + fontSize * 0.85;
  const subY = titleY + (lines.length - 1) * lineH + 55;
  const cx = Math.round(width * 0.5);
  const catWidth = catEscaped.length > 0 ? Math.max(100, catEscaped.length * 20 + 36) : 0;

  const tspans = lines
    .map((l, i) => `<tspan x="${cx}" dy="${i === 0 ? 0 : lineH}">${l}</tspan>`)
    .join("\n      ");

  let svg = "";

  // ZIDOOKA.COM label
  svg += `
  <text x="${cx}" y="${labelY + 30}" font-family="${UI_FONT}"
        font-size="21" font-weight="800" fill="${accent}" opacity="0.55"
        letter-spacing="5" text-anchor="middle">
    ZIDOOKA.COM
  </text>`;

  // Category pill
  if (hasCat) {
    svg += `
  <rect x="${cx - catWidth / 2}" y="${catY}" width="${catWidth}" height="36" rx="18"
        fill="${theme.pillBg}" opacity="0.22"/>
  <rect x="${cx - catWidth / 2}" y="${catY}" width="${catWidth}" height="36" rx="18"
        fill="none" stroke="${theme.pillBg}" stroke-width="1.5" opacity="0.5"/>
  <text x="${cx}" y="${catY + 25}" font-family="${UI_FONT}"
        font-size="18" font-weight="800" fill="${theme.pillText}" text-anchor="middle">
    ${catEscaped}
  </text>`;
  }

  // Title
  svg += `
  <text x="${cx}" y="${titleY}" font-family="${TITLE_FONT}"
        font-size="${fontSize}" font-weight="900" fill="url(#titleGrad)"
        text-anchor="middle">
      ${tspans}
  </text>`;

  // Subtitle
  if (hasSub) {
    svg += `
  <text x="${cx}" y="${subY}" font-family="${TITLE_FONT}"
        font-size="28" font-weight="500" fill="white" opacity="0.50"
        text-anchor="middle">
    ${subEscaped}
  </text>`;
  }

  return svg;
}

/**
 * Text overlay SVG (transparent bg) — for compositing on photos.
 */
function buildTextOverlaySvg(opts) {
  const { accent, width, height } = opts;
  const theme = deriveTheme(accent);

  return `<?xml version="1.0" encoding="UTF-8"?>
<svg width="${width}" height="${height}" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="titleGrad" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="${theme.titleTop}"/>
      <stop offset="100%" stop-color="${theme.titleBottom}"/>
    </linearGradient>
  </defs>
  ${buildTextElements(opts)}
  ${buildSignatureBadgeSvg(accent, width, height)}
  ${buildBarsSvg(accent, width, height)}
</svg>`;
}

/**
 * Dark overlay for photo backgrounds.
 */
function buildDarkOverlaySvg(accent, width, height) {
  return `<?xml version="1.0" encoding="UTF-8"?>
<svg width="${width}" height="${height}" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="dark" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#000" stop-opacity="0.50"/>
      <stop offset="50%" stop-color="#000" stop-opacity="0.45"/>
      <stop offset="100%" stop-color="#000" stop-opacity="0.60"/>
    </linearGradient>
    <radialGradient id="wash" cx="0.5" cy="0.45" r="0.6">
      <stop offset="0%" stop-color="${accent}" stop-opacity="0.12"/>
      <stop offset="100%" stop-color="${accent}" stop-opacity="0.02"/>
    </radialGradient>
  </defs>
  <rect width="${width}" height="${height}" fill="url(#dark)"/>
  <rect width="${width}" height="${height}" fill="url(#wash)"/>
</svg>`;
}

/**
 * Full SVG (solid dark bg) — fallback when no photo is used.
 */
function buildFullSvg(opts) {
  const { accent, width, height } = opts;
  const theme = deriveTheme(accent);
  const cx = Math.round(width * 0.5);

  return `<?xml version="1.0" encoding="UTF-8"?>
<svg width="${width}" height="${height}" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="${theme.bg1}"/>
      <stop offset="100%" stop-color="${theme.bg2}"/>
    </linearGradient>
    <linearGradient id="titleGrad" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="${theme.titleTop}"/>
      <stop offset="100%" stop-color="${theme.titleBottom}"/>
    </linearGradient>
    <radialGradient id="glow" cx="0.5" cy="0.5" r="0.45">
      <stop offset="0%" stop-color="${theme.glow}" stop-opacity="0.10"/>
      <stop offset="100%" stop-color="${theme.glow}" stop-opacity="0"/>
    </radialGradient>
  </defs>

  <rect width="${width}" height="${height}" fill="url(#bg)"/>
  <ellipse cx="${cx}" cy="${Math.round(height * 0.48)}" rx="${Math.round(width * 0.45)}" ry="${Math.round(height * 0.5)}" fill="url(#glow)"/>

  ${buildTextElements(opts)}
  ${buildSignatureBadgeSvg(accent, width, height)}
  ${buildBarsSvg(accent, width, height)}
</svg>`;
}

// ══════════════════════════════════════════════════════════
//  RENDER PIPELINES
// ══════════════════════════════════════════════════════════

async function renderWithPhoto(bgPath, opts, outputPath) {
  const { accent, width, height } = opts;
  const darkSvg = Buffer.from(buildDarkOverlaySvg(accent, width, height));
  const textSvg = Buffer.from(buildTextOverlaySvg(opts));

  await sharp(bgPath)
    .resize(width, height, { fit: "cover", position: "center" })
    .composite([
      { input: darkSvg, blend: "over" },
      { input: textSvg, blend: "over" },
    ])
    .png({ quality: 90 })
    .toFile(outputPath);
}

async function renderSolid(opts, outputPath) {
  const svg = buildFullSvg(opts);
  await sharp(Buffer.from(svg))
    .resize(opts.width, opts.height)
    .png({ quality: 90 })
    .toFile(outputPath);
}

// ── Font installer ────────────────────────────────────────
async function installFont() {
  const https = require("https");
  console.log("Downloading M PLUS 1p from Google Fonts...");
  const css = await new Promise((resolve, reject) => {
    https.get("https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@800;900&display=swap",
      { headers: { "User-Agent": "Mozilla/5.0" } },
      (res) => { let d = ""; res.on("data", (c) => (d += c)); res.on("end", () => resolve(d)); }
    ).on("error", reject);
  });
  const urls = [...css.matchAll(/url\((https:\/\/[^)]+\.ttf)\)/g)].map((m) => m[1]);
  if (!urls.length) { console.error("No font URLs found."); return; }

  const fontDir = path.join(process.env.LOCALAPPDATA, "Microsoft", "Windows", "Fonts");
  if (!fs.existsSync(fontDir)) fs.mkdirSync(fontDir, { recursive: true });

  const weights = ["800", "900"];
  for (let i = 0; i < urls.length; i++) {
    const w = weights[i] || `w${i}`;
    const outFile = path.join(fontDir, `MPLUS1p-${w}.ttf`);
    console.log(`  Downloading weight ${w}...`);
    await new Promise((resolve, reject) => {
      const https2 = require("https");
      const file = fs.createWriteStream(outFile);
      https2.get(urls[i], (res) => {
        if (res.statusCode >= 300 && res.headers.location) {
          https2.get(res.headers.location, (r2) => { r2.pipe(file); file.on("finish", () => { file.close(); resolve(); }); });
        } else { res.pipe(file); file.on("finish", () => { file.close(); resolve(); }); }
      }).on("error", reject);
    });
    try {
      require("child_process").execSync(
        `reg add "HKCU\\SOFTWARE\\Microsoft\\Windows NT\\CurrentVersion\\Fonts" /v "M PLUS 1p ${w} (TrueType)" /t REG_SZ /d "${outFile}" /f`,
        { stdio: "pipe" }
      );
    } catch (_) { }
    console.log(`  Installed: ${outFile}`);
  }
  console.log("\nDone. Restart terminal for font to take effect.");
}

// ── Main ──────────────────────────────────────────────────
async function main() {
  const args = parseArgs(process.argv.slice(2));

  if (args["install-font"]) { await installFont(); process.exit(0); }

  if (args.help) {
    console.log(`
ZIDOOKA Thumbnail Generator v5

Usage:
  node scripts/generate-thumbnail.cjs --title "..." --output path.png [options]

Required:
  --title         Main title text
  --output        Output file path (.png)

Options:
  --bg            Background image. Can be:
                    (no value)    → random pick from images/bg-stock/
                    <file path>   → use specific local image
                  If omitted entirely, uses solid dark gradient.
  --subtitle      Subtitle text
  --accent        Color preset or hex. Presets:
                    cyan | green | purple | amber | red | blue | pink | orange | teal | indigo
  --category      Category pill text
  --width         Width in px (default: 1920)
  --height        Height in px (default: 900)

Setup:
  --install-font  Download & install "M PLUS 1p" (run once)

Examples:
  # Random stock photo background
  node scripts/generate-thumbnail.cjs \\
    --title "spawn EPERMエラーの原因と対処法" \\
    --bg --accent red --category "エラー解決" \\
    --output images/2026/spawn-eperm-thumbnail.png

  # Specific background image
  node scripts/generate-thumbnail.cjs \\
    --title "My Article" --bg "images/bg-stock/laptop.png" \\
    --output images/2026/my-thumbnail.png

  # Solid background (no photo)
  node scripts/generate-thumbnail.cjs \\
    --title "GASでメール自動送信" --accent indigo \\
    --output images/2026/gas-thumbnail.png
`);
    process.exit(0);
  }

  if (!args.title) { console.error("Error: --title is required."); process.exit(1); }
  if (!args.output) { console.error("Error: --output is required."); process.exit(1); }

  let accent = args.accent || "#06b6d4";
  if (accent in PRESETS) accent = PRESETS[accent];
  else if (!accent.startsWith("#")) {
    console.warn(`Unknown accent "${accent}", using cyan.`);
    accent = "#06b6d4";
  }

  const opts = {
    title: args.title,
    subtitle: args.subtitle || "",
    accent,
    category: args.category || "",
    width: parseInt(args.width, 10) || 1920,
    height: parseInt(args.height, 10) || 900,
  };

  const outputPath = path.resolve(process.cwd(), args.output);
  const outputDir = path.dirname(outputPath);
  if (!fs.existsSync(outputDir)) fs.mkdirSync(outputDir, { recursive: true });

  // Determine background
  let bgPath = null;
  if (args.bg === true) {
    // --bg with no value → random from stock pool
    bgPath = pickRandomBg();
    if (!bgPath) {
      console.warn("No stock images in images/bg-stock/. Using solid background.");
    } else {
      console.log(`Background: ${path.basename(bgPath)} (random)`);
    }
  } else if (typeof args.bg === "string") {
    // --bg <path> → specific file
    const resolved = path.resolve(process.cwd(), args.bg);
    if (fs.existsSync(resolved)) {
      bgPath = resolved;
      console.log(`Background: ${args.bg}`);
    } else {
      console.warn(`File not found: ${args.bg}. Using solid background.`);
    }
  }

  // Render
  if (bgPath) {
    await renderWithPhoto(bgPath, opts, outputPath);
  } else {
    await renderSolid(opts, outputPath);
  }

  const stats = fs.statSync(outputPath);
  console.log(`\nCreated: ${args.output} (${(stats.size / 1024).toFixed(0)} KB)`);
  console.log(`  Title:    ${opts.title}`);
  if (opts.subtitle) console.log(`  Subtitle: ${opts.subtitle}`);
  console.log(`  Accent:   ${accent}`);
  console.log(`  Size:     ${opts.width}x${opts.height}`);
}

main().catch((err) => {
  console.error("Error:", err.message || err);
  process.exit(1);
});
