#!/usr/bin/env node
/**
 * Generate branded thumbnail images for 5 UTM blog articles.
 * Uses sharp to render SVG → PNG at 1920x900.
 */

const sharp = require("sharp");
const path = require("path");
const fs = require("fs");

const OUTPUT_DIR = path.join(__dirname, "..", "images", "2026");
const WIDTH = 1920;
const HEIGHT = 900;

// Brand colors matching tools.zidooka.com
const BRAND_CYAN = "#06b6d4";
const BRAND_DARK = "#0e7490";
const INK_900 = "#1a1a2e";
const INK_600 = "#4a4a5a";

const articles = [
  {
    filename: "utm-tools-overview-thumbnail.png",
    title: "UTMパラメータの基本と",
    subtitle: "無料ツール3種の使い方ガイド",
    icon: "link", // chain link
    accent: BRAND_CYAN,
  },
  {
    filename: "utm-generator-guide-thumbnail.png",
    title: "UTM生成ツールの使い方",
    subtitle: "入力候補・短縮URL・QRコードまで",
    icon: "plus", // build/create
    accent: "#10b981", // green
  },
  {
    filename: "qr-generator-guide-thumbnail.png",
    title: "7種類のQRコードを即生成",
    subtitle: "URL・WiFi・vCard対応の無料ツール",
    icon: "qr",
    accent: "#8b5cf6", // purple
  },
  {
    filename: "utm-parser-guide-thumbnail.png",
    title: "UTM解析ツールで",
    subtitle: "URLのパラメータを分解して確認",
    icon: "search",
    accent: "#f59e0b", // amber
  },
  {
    filename: "utm-qr-offline-tracking-thumbnail.png",
    title: "UTM+QRコードで",
    subtitle: "オフライン施策をGA4で計測する実践ガイド",
    icon: "chart",
    accent: "#ef4444", // red
  },
];

function getIconSvg(type, color) {
  const icons = {
    link: `
      <g transform="translate(860,200)" stroke="${color}" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" transform="scale(4)"/>
        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" transform="scale(4)"/>
      </g>`,
    plus: `
      <g transform="translate(870,200)" stroke="${color}" stroke-width="5" fill="none" stroke-linecap="round">
        <circle cx="48" cy="48" r="44" transform="scale(2)"/>
        <line x1="48" y1="28" x2="48" y2="68" transform="scale(2)"/>
        <line x1="28" y1="48" x2="68" y2="48" transform="scale(2)"/>
      </g>`,
    qr: `
      <g transform="translate(855,195)" fill="${color}">
        <rect x="0" y="0" width="40" height="40" rx="4"/>
        <rect x="50" y="0" width="40" height="40" rx="4"/>
        <rect x="0" y="50" width="40" height="40" rx="4"/>
        <rect x="50" y="50" width="20" height="20" rx="2"/>
        <rect x="80" y="50" width="10" height="20" rx="2"/>
        <rect x="80" y="80" width="10" height="10" rx="2"/>
        <rect x="50" y="80" width="20" height="10" rx="2"/>
        <!-- inner squares -->
        <rect x="10" y="10" width="20" height="20" rx="2" fill="white"/>
        <rect x="60" y="10" width="20" height="20" rx="2" fill="white"/>
        <rect x="10" y="60" width="20" height="20" rx="2" fill="white"/>
        <rect x="15" y="15" width="10" height="10" rx="1" fill="${color}"/>
        <rect x="65" y="15" width="10" height="10" rx="1" fill="${color}"/>
        <rect x="15" y="65" width="10" height="10" rx="1" fill="${color}"/>
      </g>`,
    search: `
      <g transform="translate(860,200)" stroke="${color}" stroke-width="5" fill="none" stroke-linecap="round">
        <circle cx="44" cy="44" r="34" transform="scale(2)"/>
        <line x1="68" y1="68" x2="88" y2="88" transform="scale(2)"/>
      </g>`,
    chart: `
      <g transform="translate(830,210)" fill="${color}">
        <rect x="0" y="60" width="25" height="40" rx="3" opacity="0.6"/>
        <rect x="35" y="30" width="25" height="70" rx="3" opacity="0.8"/>
        <rect x="70" y="10" width="25" height="90" rx="3"/>
        <rect x="105" y="40" width="25" height="60" rx="3" opacity="0.7"/>
        <rect x="140" y="20" width="25" height="80" rx="3" opacity="0.9"/>
      </g>`,
  };
  return icons[type] || icons.link;
}

function buildSvg(article) {
  return `<?xml version="1.0" encoding="UTF-8"?>
<svg width="${WIDTH}" height="${HEIGHT}" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#f8fafc"/>
      <stop offset="100%" stop-color="#e2e8f0"/>
    </linearGradient>
    <linearGradient id="accent" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0%" stop-color="${article.accent}"/>
      <stop offset="100%" stop-color="${BRAND_DARK}"/>
    </linearGradient>
  </defs>

  <!-- Background -->
  <rect width="${WIDTH}" height="${HEIGHT}" fill="url(#bg)"/>

  <!-- Accent bar at top -->
  <rect width="${WIDTH}" height="8" fill="url(#accent)"/>

  <!-- Decorative circles -->
  <circle cx="1650" cy="150" r="200" fill="${article.accent}" opacity="0.06"/>
  <circle cx="1750" cy="600" r="150" fill="${article.accent}" opacity="0.04"/>
  <circle cx="200" cy="700" r="120" fill="${article.accent}" opacity="0.04"/>

  <!-- Icon area -->
  ${getIconSvg(article.icon, article.accent)}

  <!-- Site label -->
  <text x="100" y="80" font-family="sans-serif" font-size="28" font-weight="600" fill="${INK_600}" letter-spacing="2">
    ZIDOOKA.COM
  </text>

  <!-- Category pill -->
  <rect x="100" y="340" width="160" height="40" rx="20" fill="${article.accent}" opacity="0.15"/>
  <text x="180" y="367" font-family="sans-serif" font-size="22" font-weight="700" fill="${article.accent}" text-anchor="middle">
    便利ツール
  </text>

  <!-- Title -->
  <text x="100" y="450" font-family="sans-serif" font-size="64" font-weight="900" fill="${INK_900}">
    ${article.title}
  </text>

  <!-- Subtitle -->
  <text x="100" y="540" font-family="sans-serif" font-size="48" font-weight="700" fill="${INK_600}">
    ${article.subtitle}
  </text>

  <!-- Tool badge -->
  <rect x="100" y="620" width="340" height="50" rx="12" fill="white" stroke="${article.accent}" stroke-width="2"/>
  <text x="270" y="652" font-family="sans-serif" font-size="22" font-weight="600" fill="${INK_900}" text-anchor="middle">
    tools.zidooka.com
  </text>

  <!-- Bottom accent line -->
  <rect y="${HEIGHT - 6}" width="${WIDTH}" height="6" fill="url(#accent)"/>
</svg>`;
}

async function main() {
  // Ensure output directory exists
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
  }

  for (const article of articles) {
    const svg = buildSvg(article);
    const outputPath = path.join(OUTPUT_DIR, article.filename);

    await sharp(Buffer.from(svg))
      .resize(WIDTH, HEIGHT)
      .png({ quality: 90 })
      .toFile(outputPath);

    const stats = fs.statSync(outputPath);
    console.log(`Created: ${article.filename} (${(stats.size / 1024).toFixed(0)} KB)`);
  }

  console.log(`\nAll ${articles.length} thumbnails generated in ${OUTPUT_DIR}`);
}

main().catch((err) => {
  console.error("Error:", err);
  process.exit(1);
});
