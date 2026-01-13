import { chromium } from 'playwright';
import { mkdirSync } from 'fs';
import { dirname } from 'path';

// Defaults (wide PC-oriented viewport capture)
const url = process.argv[2] || 'https://www.zidooka.com/';
const outPath = process.argv[3] || 'images-agent-browser/zidooka-pc-viewport.png';
// Default viewport mimics a typical desktop content area height (exclude browser chrome)
const width = parseInt(process.env.SCREEN_WIDTH || '1920', 10);
const height = parseInt(process.env.SCREEN_HEIGHT || '900', 10);

// Ensure output directory exists
mkdirSync(dirname(outPath), { recursive: true });

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width, height } });
  try {
    await page.goto(url, { waitUntil: 'load', timeout: 60000 });
    // Capture only the viewport (what a desktop user initially sees)
    await page.screenshot({ path: outPath, fullPage: false });
    console.log(`Saved viewport screenshot to ${outPath} (${width}x${height})`);
  } catch (err) {
    console.error('Screenshot failed:', err);
    process.exitCode = 1;
  } finally {
    await browser.close();
  }
})();
