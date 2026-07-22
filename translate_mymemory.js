const fs = require('fs');
const http = require('http');
const https = require('https');

const dbPath = './src/data/db.json';
const db = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

function flattenObject(obj, prefix = []) {
  let result = [];
  for (const key in obj) {
    if (typeof obj[key] === 'string') {
      if (!obj[key].startsWith('http') && !obj[key].startsWith('/') && !obj[key].startsWith('#') && /[a-zA-Z]/.test(obj[key])) {
        result.push({ path: [...prefix, key], value: obj[key] });
      }
    } else if (typeof obj[key] === 'object' && obj[key] !== null) {
      result = result.concat(flattenObject(obj[key], [...prefix, key]));
    }
  }
  return result;
}

const translateWithMyMemory = (text) => {
  return new Promise((resolve, reject) => {
    const url = `https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=en|ar&de=fahamayoub@gmail.com`;
    https.get(url, (res) => {
      let data = '';
      res.on('data', (chunk) => data += chunk);
      res.on('end', () => {
        try {
          const json = JSON.parse(data);
          if (json.responseData && json.responseData.translatedText) {
            resolve(json.responseData.translatedText);
          } else {
            reject(new Error("No translated text in response"));
          }
        } catch (e) {
          reject(e);
        }
      });
    }).on('error', reject);
  });
};

async function run() {
  const newLangDb = JSON.parse(JSON.stringify(db.en));
  const stringsToTranslate = flattenObject(newLangDb);
  
  console.log(`Translating ${stringsToTranslate.length} strings using MyMemory API...`);

  for (let i = 0; i < stringsToTranslate.length; i++) {
    const item = stringsToTranslate[i];
    let success = false;
    let retries = 3;
    
    // Check if it was already translated by my earlier script!
    // No, because the db.ar got completely overwritten with english!
    
    while (!success && retries > 0) {
      try {
        const translatedText = await translateWithMyMemory(item.value);
        console.log(`[${i+1}/${stringsToTranslate.length}] Translated: ${item.value.substring(0, 20)}... -> ${translatedText.substring(0, 20)}...`);
        
        let current = newLangDb;
        for (let k = 0; k < item.path.length - 1; k++) {
          current = current[item.path[k]];
        }
        current[item.path[item.path.length - 1]] = translatedText;
        
        success = true;
        // Small delay to be polite
        await new Promise(r => setTimeout(r, 100));
      } catch (err) {
        retries--;
        console.error(`Error translating item ${i}, retries left ${retries}:`, err.message);
        if (retries > 0) await new Promise(r => setTimeout(r, 2000));
      }
    }
  }

  db['ar'] = newLangDb;
  fs.writeFileSync(dbPath, JSON.stringify(db, null, 2));
  console.log("Done! Arabic translation fully saved.");
}

run();
