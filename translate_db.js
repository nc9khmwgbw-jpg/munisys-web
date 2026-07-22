const fs = require('fs');
const https = require('https');

const dbPath = './src/data/db.json';
const db = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

async function translateText(text) {
  if (typeof text !== 'string' || text.trim() === '') return text;
  
  // Quick skip for things that are just numbers or URLs
  if (/^[\d\s]+$/.test(text)) return text;
  if (text.startsWith('http') || text.startsWith('/')) return text;
  
  return new Promise((resolve, reject) => {
    const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=ar&dt=t&q=${encodeURIComponent(text)}`;
    
    https.get(url, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        try {
          const json = JSON.parse(data);
          let translated = '';
          if (json && json[0]) {
            json[0].forEach(item => {
              if (item[0]) translated += item[0];
            });
            resolve(translated);
          } else {
            resolve(text); // fallback
          }
        } catch (e) {
          resolve(text);
        }
      });
    }).on('error', () => resolve(text));
  });
}

const sleep = ms => new Promise(r => setTimeout(r, ms));

async function processObject(enObj, arObj) {
  const keys = Object.keys(enObj);
  for (const key of keys) {
    if (typeof enObj[key] === 'string') {
      // Translate if missing or identical to english (assuming it wasn't translated)
      if (!arObj.hasOwnProperty(key) || arObj[key] === enObj[key] || !arObj[key]) {
        console.log(`Translating: ${enObj[key]}`);
        arObj[key] = await translateText(enObj[key]);
        await sleep(300); // Prevent rate limiting
      }
    } else if (Array.isArray(enObj[key])) {
      if (!arObj[key]) arObj[key] = [];
      for (let i = 0; i < enObj[key].length; i++) {
        if (typeof enObj[key][i] === 'string') {
          if (!arObj[key][i] || arObj[key][i] === enObj[key][i]) {
            console.log(`Translating: ${enObj[key][i]}`);
            arObj[key][i] = await translateText(enObj[key][i]);
            await sleep(300);
          }
        } else if (typeof enObj[key][i] === 'object') {
          if (!arObj[key][i]) arObj[key][i] = {};
          await processObject(enObj[key][i], arObj[key][i]);
        }
      }
    } else if (typeof enObj[key] === 'object' && enObj[key] !== null) {
      if (!arObj[key]) arObj[key] = {};
      await processObject(enObj[key], arObj[key]);
    }
  }
}

async function main() {
  console.log("Starting translation...");
  await processObject(db.en, db.ar);
  fs.writeFileSync(dbPath, JSON.stringify(db, null, 2));
  console.log("Translation finished!");
}

main();
