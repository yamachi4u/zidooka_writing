import { chromium } from 'playwright';
const browser = await chromium.launch({ headless: true });
const cases = [
  ['desktop-light-final', 1440, 900, 'light'],
  ['desktop-dark-final', 1440, 900, 'dark'],
  ['mobile-dark-final', 390, 844, 'dark'],
];
for (const [name, width, height, scheme] of cases) {
  const page = await browser.newPage({ viewport: { width, height }, colorScheme: scheme, deviceScaleFactor: 1 });
  await page.goto('https://www.zidooka.com/?prime_visual=' + Date.now(), { waitUntil: 'networkidle', timeout: 60000 });
  const info = await page.locator('body').evaluate(() => {
    const banner = document.querySelector('.zdk-prime-day-banner');
    const link = document.querySelector('.zdk-prime-day-banner__link');
    const img = document.querySelector('.zdk-prime-day-banner__image');
    const rect = el => {
      if (!el) return null;
      const r = el.getBoundingClientRect();
      const cs = getComputedStyle(el);
      return { width: Math.round(r.width), height: Math.round(r.height), y: Math.round(r.y), background: cs.backgroundColor, padding: cs.padding };
    };
    return { banner: rect(banner), link: rect(link), img: rect(img) };
  });
  await page.screenshot({ path: `images-agent-browser/prime-zidooka-${name}.png`, fullPage: false });
  console.log(name, JSON.stringify(info));
  await page.close();
}
await browser.close();