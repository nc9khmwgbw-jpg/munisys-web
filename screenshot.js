const puppeteer = require('puppeteer');
(async () => {
  const browser = await puppeteer.launch({ headless: 'new', args: ['--no-sandbox'] });
  const page = await browser.newPage();
  await page.setViewport({ width: 1440, height: 900 });
  await page.goto('http://localhost:3000', { waitUntil: 'networkidle2' });
  await page.screenshot({ path: 'map_screenshot.png' });
  await browser.close();
  console.log('Screenshot saved to map_screenshot.png');
})();
