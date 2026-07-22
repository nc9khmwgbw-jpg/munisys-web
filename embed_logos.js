const fs = require('fs');
const https = require('https');
const http = require('http');

const dbPath = './src/data/db.json';
let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const fetchAsBase64 = (url) => {
  return new Promise((resolve, reject) => {
    const client = url.startsWith('https') ? https : http;
    client.get(url, { headers: { 'User-Agent': 'Mozilla/5.0' } }, (res) => {
      if (res.statusCode >= 300 && res.statusCode < 400 && res.headers.location) {
        // Handle redirect
        resolve(fetchAsBase64(res.headers.location));
        return;
      }
      
      if (res.statusCode !== 200) {
        resolve(null); // Fallback if failed
        return;
      }
      
      const contentType = res.headers['content-type'];
      const chunks = [];
      res.on('data', chunk => chunks.push(chunk));
      res.on('end', () => {
        const buffer = Buffer.concat(chunks);
        resolve(`data:${contentType};base64,${buffer.toString('base64')}`);
      });
    }).on('error', () => resolve(null));
  });
};

const makeBase64Uri = (svg) => 'data:image/svg+xml;base64,' + Buffer.from(svg).toString('base64');
const makeLogo = (text, color) => makeBase64Uri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60"><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="24" fill="${color}">${text}</text></svg>`);

const fallbackLogos = {
  vmware: makeLogo('vmware', '#607078'),
  fortinet: makeLogo('FORTINET', '#E31837'),
  boa: makeLogo('BANK OF AFRICA', '#003b70'),
  bam: makeLogo('Bank Al-Maghrib', '#1a4e2b'),
  awb: makeLogo('Attijariwafa', '#d88b22'),
  marbio: makeLogo('Marbio', '#3f80c0'),
  inwi: makeLogo('inwi', '#e3000f')
};

async function run() {
  console.log('Downloading logos as base64...');
  
  const bp = await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/2/2f/Logo_BCP.svg') || await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Logo_BCP.svg/512px-Logo_BCP.svg.png');
  const cdg = await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/7/77/CDG.svg') || await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/CDG.svg/512px-CDG.svg.png');
  const oncf = await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/0/05/Logo-oncf.png');
  const ocp = await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/1/1c/OCP_Group.svg') || await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/OCP_Group.svg/512px-OCP_Group.svg.png');
  const lafarge = await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/3/30/LafargeHolcim_logo.svg') || await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/LafargeHolcim_logo.svg/512px-LafargeHolcim_logo.svg.png');
  
  // For the problematic ones, try to get them, but fallback to text if they fail or timeout
  const boa = await fetchAsBase64('https://logo.clearbit.com/bankofafrica.ma') || fallbackLogos.boa;
  const bam = await fetchAsBase64('https://logo.clearbit.com/bkam.ma') || fallbackLogos.bam;
  const awb = await fetchAsBase64('https://logo.clearbit.com/attijariwafabank.com') || fallbackLogos.awb;
  const marbio = await fetchAsBase64('https://logo.clearbit.com/marbio.ma') || fallbackLogos.marbio;
  const inwi = await fetchAsBase64('https://logo.clearbit.com/inwi.ma') || fallbackLogos.inwi;
  
  const vmware = await fetchAsBase64('https://logo.clearbit.com/vmware.com') || fallbackLogos.vmware;
  const fortinet = await fetchAsBase64('https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Fortinet_logo.svg/512px-Fortinet_logo.svg.png') || fallbackLogos.fortinet;
  
  const updatedCustomers = {
    "Banque Populaire": bp,
    "Bank Of Africa": boa,
    "Bank Al-Maghrib": bam,
    "Attijariwafa Bank": awb,
    "CDG": cdg,
    "ONCF": oncf,
    "OCP": ocp,
    "Marbio": marbio,
    "LafargeHolcim": lafarge,
    "Inwi": inwi
  };
  
  ['en', 'fr', 'ar'].forEach(lang => {
    if (data[lang] && data[lang].customerReferences) {
      data[lang].customerReferences.references.forEach(r => {
        // match by english name key
        const englishNameKey = Object.keys(updatedCustomers).find(k => k.toLowerCase() === r.name.toLowerCase() || (lang === 'ar' && updatedCustomers[k]));
        if (englishNameKey) {
           r.logo = updatedCustomers[englishNameKey];
        }
      });
      // specific fix for arabic names mapping
      if (lang === 'ar') {
         const refs = data[lang].customerReferences.references;
         refs[0].logo = bp;
         refs[1].logo = boa;
         refs[2].logo = bam;
         refs[3].logo = awb;
         refs[4].logo = cdg;
         refs[5].logo = oncf;
         refs[6].logo = ocp;
         refs[7].logo = marbio;
         refs[8].logo = lafarge;
         refs[9].logo = inwi;
      }
    }
  });

  fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
  console.log('Embedded all logos as Base64!');
}

run();
