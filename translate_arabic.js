const fs = require('fs');
const translateApi = require('@vitalets/google-translate-api');

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

async function run() {
  const newLangDb = JSON.parse(JSON.stringify(db.en));
  const stringsToTranslate = flattenObject(newLangDb);
  
  const chunks = [];
  let currentChunk = [];
  let currentCharCount = 0;

  for (const item of stringsToTranslate) {
    if (currentCharCount + item.value.length > 2000) {
      chunks.push(currentChunk);
      currentChunk = [item];
      currentCharCount = item.value.length;
    } else {
      currentChunk.push(item);
      currentCharCount += item.value.length;
    }
  }
  if (currentChunk.length > 0) chunks.push(currentChunk);

  console.log(`Translating ${stringsToTranslate.length} strings in ${chunks.length} chunks to ar...`);

  for (let i = 0; i < chunks.length; i++) {
    const chunk = chunks[i];
    const textToTranslate = chunk.map(c => c.value).join(' ||| ');
    
    let success = false;
    let retries = 3;
    while (!success && retries > 0) {
      try {
        console.log(`Translating chunk ${i + 1}/${chunks.length}...`);
        const res = await translateApi.translate(textToTranslate, { to: 'ar' });
        const translatedParts = res.text.split(/\|\|\|/g).map(s => s.trim());
        
        for (let j = 0; j < chunk.length; j++) {
          const item = chunk[j];
          let translatedText = translatedParts[j] || item.value;
          
          let current = newLangDb;
          for (let k = 0; k < item.path.length - 1; k++) {
            current = current[item.path[k]];
          }
          current[item.path[item.path.length - 1]] = translatedText;
        }
        success = true;
        await new Promise(r => setTimeout(r, 2500)); // 2.5s delay to avoid 429
      } catch (chunkErr) {
        retries--;
        console.error(`Error translating chunk ${i}, retries left ${retries}:`, chunkErr.message);
        if (retries > 0) await new Promise(r => setTimeout(r, 5000));
      }
    }
    if (!success) {
      console.log("FAILED TO TRANSLATE CHUNK", i);
    }
  }

  db['ar'] = newLangDb;
  fs.writeFileSync(dbPath, JSON.stringify(db, null, 2));
  console.log("Done! Arabic translation fully saved.");
}

run();
