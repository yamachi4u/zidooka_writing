// scripts/agent-browser-gallery.mjs
import { chromium } from 'playwright';
import { mkdirSync } from 'fs';

const url = 'https://www.zidooka.com/';
const outputDir = 'images-agent-browser';

mkdirSync(outputDir, { recursive: true });

(async () => {
    console.log('Launching browser...');
    const browser = await chromium.launch();
    
    // 1. Full Page
    try {
        console.log('Taking full page...');
        const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 });
        // Give it a bit more time for lazy load
        await page.waitForTimeout(2000);
        await page.screenshot({ path: `${outputDir}/zidooka-full.png`, fullPage: true });
        console.log('Saved zidooka-full.png');
        await page.close();
    } catch (e) {
        console.error('Error full:', e);
    }

    // 2. Mobile (iPhone 14 estimate)
    try {
        console.log('Taking mobile...');
        const context = await browser.newContext({
             userAgent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1',
             viewport: { width: 390, height: 844 },
             isMobile: true
        });
        const page = await context.newPage();
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 });
        await page.waitForTimeout(2000);
        await page.screenshot({ path: `${outputDir}/zidooka-mobile.png`, fullPage: false });
        console.log('Saved zidooka-mobile.png');
        await page.close();
    } catch (e) {
        console.error('Error mobile:', e);
    }

    // 3. Tablet (iPad estimate)
    try {
        console.log('Taking tablet...');
        const page = await browser.newPage({ viewport: { width: 820, height: 1180 } });
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 });
        await page.waitForTimeout(2000);
        await page.screenshot({ path: `${outputDir}/zidooka-tablet.png`, fullPage: false });
        console.log('Saved zidooka-tablet.png');
        await page.close();
    } catch (e) {
        console.error('Error tablet:', e);
    }

    await browser.close();
    console.log('Done.');
})();
