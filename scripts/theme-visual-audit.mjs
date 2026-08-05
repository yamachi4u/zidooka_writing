import { chromium } from 'playwright';
import fs from 'node:fs/promises';
import path from 'node:path';

const phase = process.argv[2] || 'current';
const outDir = path.join(process.cwd(), 'images-agent-browser', 'theme-audit-20260711', phase);
await fs.mkdir(outDir, { recursive: true });

const pages = [
  ['home', 'https://www.zidooka.com/'],
  ['post-en', 'https://www.zidooka.com/archives/4575'],
  ['post-ja', 'https://www.zidooka.com/archives/4582'],
];
const viewports = [
  ['desktop-light', 1440, 900, 'light'],
  ['desktop-dark', 1440, 900, 'dark'],
  ['tablet-light', 834, 1112, 'light'],
  ['mobile-light', 390, 844, 'light'],
  ['mobile-dark', 390, 844, 'dark'],
];

const browser = await chromium.launch({ headless: true });
const report = [];
for (const [pageName, baseUrl] of pages) {
  for (const [viewportName, width, height, colorScheme] of viewports) {
    const context = await browser.newContext({ viewport: { width, height }, colorScheme, deviceScaleFactor: 1 });
    const page = await context.newPage();
    const url = `${baseUrl}${baseUrl.includes('?') ? '&' : '?'}zdk_audit=${Date.now()}`;
    const errors = [];
    page.on('pageerror', error => errors.push(String(error)));
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.addStyleTag({ content: '*,*::before,*::after{animation-duration:0s!important;transition-duration:0s!important}' });
    await page.waitForTimeout(1800);

    const metrics = await page.evaluate(() => {
      const rect = selector => {
        const el = document.querySelector(selector);
        if (!el) return null;
        const r = el.getBoundingClientRect();
        return { x: Math.round(r.x), y: Math.round(r.y), width: Math.round(r.width), height: Math.round(r.height) };
      };
      const overflowing = [...document.querySelectorAll('body *')].filter(el => {
        const r = el.getBoundingClientRect();
        const cs = getComputedStyle(el);
        if (cs.display === 'none' || cs.visibility === 'hidden' || r.left < -1000) return false;
        return r.width > 0 && (r.right > document.documentElement.clientWidth + 1 || r.left < -1);
      }).slice(0, 20).map(el => ({ tag: el.tagName, className: String(el.className || '').slice(0, 120), rect: { left: Math.round(el.getBoundingClientRect().left), right: Math.round(el.getBoundingClientRect().right), width: Math.round(el.getBoundingClientRect().width) } }));
      return {
        title: document.title,
        viewport: { width: innerWidth, height: innerHeight },
        document: { clientWidth: document.documentElement.clientWidth, scrollWidth: document.documentElement.scrollWidth, scrollHeight: document.documentElement.scrollHeight },
        overflowCount: overflowing.length,
        overflowing,
        header: rect('.zdk-site-header'),
        campaign: rect('.zdk-ad--campaign_top'),
        main: rect('.zenn-main-column, main'),
        sidebar: rect('.zenn-sidebar-column'),
        sidebarAd: rect('.zdk-ad--sidebar'),
        footer: rect('footer'),
      };
    });

    const stem = `${pageName}-${viewportName}`;
    await page.screenshot({ path: path.join(outDir, `${stem}-viewport.png`), fullPage: false });
    await page.screenshot({ path: path.join(outDir, `${stem}-full.png`), fullPage: true });
    report.push({ pageName, viewportName, colorScheme, url: baseUrl, errors, ...metrics });
    console.log(stem, JSON.stringify({ overflowCount: metrics.overflowCount, scrollWidth: metrics.document.scrollWidth, clientWidth: metrics.document.clientWidth, campaign: metrics.campaign, sidebarAd: metrics.sidebarAd, errors: errors.length }));
    await context.close();
  }
}
await browser.close();
await fs.writeFile(path.join(outDir, 'metrics.json'), JSON.stringify(report, null, 2));