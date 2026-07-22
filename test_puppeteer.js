const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto('http://localhost:3000');
  
  // Wait for the admin toggle and click it
  await page.waitForSelector('#munisys-admin-toggle');
  await page.click('#munisys-admin-toggle');
  
  // Wait for the design tab and click it
  await page.waitForSelector('button::-p-text(Design)');
  const designTab = await page.$('button::-p-text(Design)');
  if (designTab) await designTab.click();
  
  // Wait for the select element
  await page.waitForSelector('select');
  
  // Get initial font family of the body
  let initialFont = await page.evaluate(() => window.getComputedStyle(document.body).fontFamily);
  let selectValue = await page.evaluate(() => document.querySelector('select').value);
  console.log('Initial Font on body:', initialFont);
  console.log('Initial Select Value:', selectValue);
  
  // Change the select value to Inter
  await page.select('select', 'Inter');
  
  // Wait a little bit for React to render
  await new Promise(r => setTimeout(r, 500));
  
  // Get new font family of the body
  let newFont = await page.evaluate(() => window.getComputedStyle(document.body).fontFamily);
  let newSelectValue = await page.evaluate(() => document.querySelector('select').value);
  console.log('New Font on body:', newFont);
  console.log('New Select Value:', newSelectValue);
  
  await browser.close();
})();
