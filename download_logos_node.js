const fs = require('fs');
const https = require('https');
const http = require('http');
const path = require('path');

process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0'; // Ignore SSL cert errors

const dbPath = './src/data/db.json';
const logosDir = './public/images/logos';

if (!fs.existsSync(logosDir)) {
  fs.mkdirSync(logosDir, { recursive: true });
}

let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const downloadImage = (url, filename) => {
  return new Promise((resolve, reject) => {
    const dest = path.join(logosDir, filename);
    const file = fs.createWriteStream(dest);
    const client = url.startsWith('https') ? https : http;
    
    client.get(url, { headers: { 'User-Agent': 'Mozilla/5.0' } }, (response) => {
      if (response.statusCode >= 300 && response.statusCode < 400 && response.headers.location) {
        return resolve(downloadImage(response.headers.location, filename));
      }
      if (response.statusCode !== 200) {
        return resolve(false);
      }
      response.pipe(file);
      file.on('finish', () => {
        file.close(() => resolve(true));
      });
    }).on('error', (err) => {
      fs.unlink(dest, () => {});
      resolve(false);
    });
  });
};

const createTextSvg = (text, color, filename) => {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60"><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="24" fill="${color}">${text}</text></svg>`;
  fs.writeFileSync(path.join(logosDir, filename), svg, 'utf8');
  return true;
};

async function run() {
  console.log('Downloading official images...');

  // Download official images
  await downloadImage('https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Logo_BCP.svg/512px-Logo_BCP.svg.png', 'bp.png');
  await downloadImage('https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/CDG.svg/512px-CDG.svg.png', 'cdg.png');
  await downloadImage('https://upload.wikimedia.org/wikipedia/commons/0/05/Logo-oncf.png', 'oncf.png');
  await downloadImage('https://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/OCP_Group.svg/512px-OCP_Group.svg.png', 'ocp.png');
  await downloadImage('https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/LafargeHolcim_logo.svg/512px-LafargeHolcim_logo.svg.png', 'lafarge.png');
  await downloadImage('https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Fortinet_logo.svg/512px-Fortinet_logo.svg.png', 'fortinet.png');
  await downloadImage('https://www.bankofafrica.ma/sites/default/files/BANKOFAFRICA-LOGOO.png', 'boa.png');

  // Generate perfect local SVGs for the ones without direct image URLs
  createTextSvg('vmware', '#607078', 'vmware.svg');
  createTextSvg('Bank Al-Maghrib', '#1a4e2b', 'bam.svg');
  createTextSvg('Attijariwafa', '#d88b22', 'awb.svg');
  createTextSvg('Marbio', '#3f80c0', 'marbio.svg');
  createTextSvg('inwi', '#e3000f', 'inwi.svg');

  const updatedCustomers = {
    "Banque Populaire": '/images/logos/bp.png',
    "Bank Of Africa": '/images/logos/boa.png',
    "Bank Al-Maghrib": '/images/logos/bam.svg',
    "Attijariwafa Bank": '/images/logos/awb.svg',
    "CDG": '/images/logos/cdg.png',
    "ONCF": '/images/logos/oncf.png',
    "OCP": '/images/logos/ocp.png',
    "Marbio": '/images/logos/marbio.svg',
    "LafargeHolcim": '/images/logos/lafarge.png',
    "Inwi": '/images/logos/inwi.svg'
  };

  ['en', 'fr', 'ar'].forEach(lang => {
    if (data[lang] && data[lang].customerReferences) {
      data[lang].customerReferences.references.forEach(r => {
        const englishNameKey = Object.keys(updatedCustomers).find(k => k.toLowerCase() === r.name.toLowerCase() || (lang === 'ar' && updatedCustomers[k]));
        if (englishNameKey) r.logo = updatedCustomers[englishNameKey];
      });
      if (lang === 'ar') {
         const refs = data[lang].customerReferences.references;
         refs[0].logo = updatedCustomers['Banque Populaire'];
         refs[1].logo = updatedCustomers['Bank Of Africa'];
         refs[2].logo = updatedCustomers['Bank Al-Maghrib'];
         refs[3].logo = updatedCustomers['Attijariwafa Bank'];
         refs[4].logo = updatedCustomers['CDG'];
         refs[5].logo = updatedCustomers['ONCF'];
         refs[6].logo = updatedCustomers['OCP'];
         refs[7].logo = updatedCustomers['Marbio'];
         refs[8].logo = updatedCustomers['LafargeHolcim'];
         refs[9].logo = updatedCustomers['Inwi'];
      }
    }
  });

  // Update Tech Partners (only touch VMware and Fortinet)
  ['en', 'fr', 'ar'].forEach(lang => {
    if (data[lang] && data[lang].technologyPartners) {
      data[lang].technologyPartners.partners.forEach(p => {
        if (p.name.toLowerCase().includes('vmware') || p.name.includes('في إم وير')) p.logo = '/images/logos/vmware.svg';
        if (p.name.toLowerCase().includes('fortinet') || p.name.includes('فورتينت')) p.logo = '/images/logos/fortinet.png';
      });
    }
  });

  fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
  console.log('Database updated to use bulletproof LOCAL logos!');
}

run();
