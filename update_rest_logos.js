const fs = require('fs');

const dbPath = './src/data/db.json';
let data = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

const getClearbit = (domain) => `https://logo.clearbit.com/${domain}`;

// New Logo assignments
const newLogos = {
  vmware: getClearbit('vmware.com'),
  fortinet: 'https://upload.wikimedia.org/wikipedia/commons/6/62/Fortinet_logo.svg',
  boa: 'https://www.bankofafrica.ma/sites/default/files/BANKOFAFRICA-LOGOO.png',
  bam: getClearbit('bkam.ma'),
  awb: getClearbit('attijariwafabank.com'),
  marbio: getClearbit('marbio.ma'),
  inwi: getClearbit('inwi.ma')
};

// Update Technology Partners
['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].technologyPartners) {
    const partners = data[lang].technologyPartners.partners;
    partners.forEach(p => {
      // We rely on the English name or partial match to update regardless of language
      if (p.name.toLowerCase().includes('vmware') || p.name.includes('في إم وير')) {
        p.logo = newLogos.vmware;
      }
      if (p.name.toLowerCase().includes('fortinet') || p.name.includes('فورتينت')) {
        p.logo = newLogos.fortinet;
      }
    });
  }
});

// Update Customer References
['en', 'fr', 'ar'].forEach(lang => {
  if (data[lang] && data[lang].customerReferences) {
    const refs = data[lang].customerReferences.references;
    refs.forEach(r => {
      if (r.name.toLowerCase().includes('bank of africa') || r.name.includes('أفريقيا')) {
        r.logo = newLogos.boa;
      }
      if (r.name.toLowerCase().includes('maghrib') || r.name.includes('بنك المغرب')) {
        r.logo = newLogos.bam;
      }
      if (r.name.toLowerCase().includes('attijariwafa') || r.name.includes('التجاري')) {
        r.logo = newLogos.awb;
      }
      if (r.name.toLowerCase().includes('marbio') || r.name.includes('ماربيو')) {
        r.logo = newLogos.marbio;
      }
      if (r.name.toLowerCase().includes('inwi') || r.name.includes('إنوي')) {
        r.logo = newLogos.inwi;
      }
    });
  }
});

fs.writeFileSync(dbPath, JSON.stringify(data, null, 2), 'utf8');
console.log('Rest of the logos updated successfully!');
