import { chromium } from 'playwright';
const browser = await chromium.launch({ headless: true });
for (const [name, width, height] of [['desktop', 1920, 900], ['mobile', 390, 844]]) {
  const page = await browser.newPage({ viewport: { width, height }, deviceScaleFactor: 1 });
  await page.goto('https://www.zidooka.com/?prime_rect=1', { waitUntil: 'networkidle', timeout: 60000 });
  const info = await page.locator('body').evaluate(() => {
    const banner = document.querySelector('.zdk-prime-day-banner');
    const link = document.querySelector('.zdk-prime-day-banner__link');
    const img = document.querySelector('.zdk-prime-day-banner__image');
    const rect = el => {
      if (!el) return null;
      const r = el.getBoundingClientRect();
      const cs = getComputedStyle(el);
      return { x: r.x, y: r.y, width: r.width, height: r.height, display: cs.display, padding: cs.padding, background: cs.backgroundColor };
    };
    return { banner: rect(banner), link: rect(link), img: rect(img) };
  });
  console.log(name, JSON.stringify(info));
  await page.screenshot({ path: `images-agent-browser/prime-zidooka-${name}-before.png`, fullPage: false });
  await page.close();
}
await browser.close();