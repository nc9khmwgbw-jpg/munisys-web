import { NextResponse } from 'next/server';
import fs from 'fs';
import path from 'path';

const dbPath = path.join(process.cwd(), 'src/data/db.json');

// Helper to flatten object
function flattenObject(obj, prefix = []) {
  let result = [];
  for (const key in obj) {
    if (typeof obj[key] === 'string') {
      // Basic filtering: skip URLs, colors, very short strings
      if (!obj[key].startsWith('http') && !obj[key].startsWith('/') && !obj[key].startsWith('#') && /[a-zA-Z]/.test(obj[key])) {
        result.push({ path: [...prefix, key], value: obj[key] });
      }
    } else if (typeof obj[key] === 'object' && obj[key] !== null) {
      result = result.concat(flattenObject(obj[key], [...prefix, key]));
    }
  }
  return result;
}

export async function POST(req) {
  try {
    const { targetLang } = await req.json();
    if (!targetLang) return NextResponse.json({ error: "Missing targetLang" }, { status: 400 });

    const rawData = fs.readFileSync(dbPath, 'utf8');
    const db = JSON.parse(rawData);

    if (!db.en) return NextResponse.json({ error: "No english base found" }, { status: 400 });

    // Clone base structure
    const newLangDb = JSON.parse(JSON.stringify(db.en));
    
    // Flatten translatable strings
    const stringsToTranslate = flattenObject(newLangDb);
    
    // Chunk into ~3000 char blocks
    const chunks = [];
    let currentChunk = [];
    let currentCharCount = 0;

    for (const item of stringsToTranslate) {
      if (currentCharCount + item.value.length > 3000) {
        chunks.push(currentChunk);
        currentChunk = [item];
        currentCharCount = item.value.length;
      } else {
        currentChunk.push(item);
        currentCharCount += item.value.length;
      }
    }
    if (currentChunk.length > 0) chunks.push(currentChunk);

    console.log(`Translating ${stringsToTranslate.length} strings in ${chunks.length} chunks to ${targetLang}...`);

    for (let i = 0; i < chunks.length; i++) {
      const chunk = chunks[i];
      const textToTranslate = chunk.map(c => c.value).join(' ||| ');
      
      try {
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=${targetLang}&dt=t&q=${encodeURIComponent(textToTranslate)}`;
        const fetchRes = await fetch(url);
        
        if (!fetchRes.ok) {
          throw new Error(`Translate API returned ${fetchRes.status}`);
        }
        
        const data = await fetchRes.json();
        const translatedText = data[0].map(item => item[0]).join('');
        
        // Split by the delimiter. Sometimes translation adds spaces or alters it slightly.
        const translatedParts = translatedText.split(/\|\|\||\| \| \|/g).map(s => s.trim());
        
        for (let j = 0; j < chunk.length; j++) {
          const item = chunk[j];
          let translatedText = translatedParts[j] || item.value;
          
          let current = newLangDb;
          for (let k = 0; k < item.path.length - 1; k++) {
            current = current[item.path[k]];
          }
          current[item.path[item.path.length - 1]] = translatedText;
        }
        
        // Wait 1 second between chunks to avoid rate limits
        if (i < chunks.length - 1) {
          await new Promise(r => setTimeout(r, 1000));
        }
      } catch (chunkErr) {
        console.error(`Error translating chunk ${i}:`, chunkErr);
      }
    }

    db[targetLang] = newLangDb;

    // Persist via the content API so both MongoDB and local fallback are updated
    const saveRes = await fetch(`${process.env.NEXT_PUBLIC_BASE_URL || 'http://localhost:3000'}/api/content`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(db)
    });

    if (!saveRes.ok) {
      // Fallback: write to local file if API save fails
      fs.writeFileSync(dbPath, JSON.stringify(db, null, 2));
    }

    return NextResponse.json({ success: true, db: db });

  } catch (error) {
    console.error("Translation API Error:", error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
